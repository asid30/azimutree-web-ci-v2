"""Validate the GitHub Pages artifact without PHP or external dependencies."""
from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import unquote, urlsplit

ROOT = Path(__file__).resolve().parents[1] / 'site'

class Document(HTMLParser):
    def __init__(self, text):
        super().__init__()
        self.refs = []
        self.ids = set()
        self.feed(text)

    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if 'id' in attrs:
            self.ids.add(attrs['id'])
        for attr in ('href', 'src', 'action'):
            if attrs.get(attr):
                self.refs.append(attrs[attr])

pages = {}
for path in ROOT.rglob('*'):
    if not path.is_file():
        continue
    assert not path.is_symlink(), path
    assert path.suffix.lower() not in {'.php', '.sql', '.db', '.log', '.xlsx'}, path
    assert path.name not in {'.env', '.htaccess'}, path
    if path.suffix == '.html':
        text = path.read_text()
        assert '<?' not in text, f'PHP left in {path}'
        expected_lang = 'en' if path.parent.name == 'privacy-policy' else 'id'
        assert f'<html lang="{expected_lang}">' in text, path
        pages[path.resolve()] = Document(text)

for path, doc in pages.items():
    for ref in doc.refs:
        u = urlsplit(ref)
        if u.scheme or u.netloc:
            continue
        assert not u.path.startswith('/'), f'Root-relative URL fails project Pages: {path}: {ref}'
        target = (path.parent / unquote(u.path)).resolve() if u.path else path
        assert target.is_relative_to(ROOT.resolve()), (path, ref)
        if target.is_dir():
            target /= 'index.html'
        assert target.is_file(), f'Missing target: {path}: {ref}'
        if u.fragment and target in pages:
            assert unquote(u.fragment) in pages[target].ids, (path, ref)
assert not (ROOT / 'repositori-data').exists()
required_pages = {'index.html', 'about/index.html', 'guide/index.html',
                  'template/index.html', 'account-deletion/index.html', 'privacy-policy/index.html',
                  'easteregg/secret/index.html', 'easteregg/mybestie/index.html', '404.html'}
assert required_pages <= {str(p.relative_to(ROOT.resolve())) for p in pages}
print(f'PASS: {len(pages)} HTML pages; local links, assets, anchors, and publish boundary verified.')
