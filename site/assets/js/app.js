// Minimal JS for Azimutree landing page
document.addEventListener("DOMContentLoaded", function () {
  // Menu toggle
  var btn = document.getElementById("menuToggle");
  var menu = document.getElementById("menu");
  if (btn && menu) {
    btn.addEventListener("click", function (e) {
      e.stopPropagation();
      menu.classList.toggle("hidden");
      menu.setAttribute("aria-hidden", menu.classList.contains("hidden"));
    });

    // Close menu when a link inside is clicked (mobile navigation)
    menu.addEventListener("click", function (e) {
      var target = e.target;
      if (target && target.classList && target.classList.contains("app-link")) {
        menu.classList.add("hidden");
        menu.setAttribute("aria-hidden", "true");
      }
    });

    // Close menu when tapping outside
    document.addEventListener("click", function (e) {
      if (!menu.classList.contains("hidden")) {
        var isClickInside = menu.contains(e.target) || btn.contains(e.target);
        if (!isClickInside) {
          menu.classList.add("hidden");
          menu.setAttribute("aria-hidden", "true");
        }
      }
    });
  }

  // Small dropdowns for repository auth/profile controls
  (function () {
    var triggers = document.querySelectorAll("[data-dropdown-toggle]");
    if (!triggers || triggers.length === 0) return;

    function closeDropdowns(exceptId) {
      triggers.forEach(function (trigger) {
        var id = trigger.getAttribute("data-dropdown-toggle");
        var dropdown = document.getElementById(id);
        var backdrops = document.querySelectorAll('[data-dropdown-close="' + id + '"]');
        if (!dropdown || id === exceptId) return;
        dropdown.classList.add("hidden");
        trigger.setAttribute("aria-expanded", "false");
        backdrops.forEach(function (backdrop) {
          if (backdrop.classList.contains("repo-upload-backdrop")) {
            backdrop.classList.add("hidden");
          }
        });
      });
    }

    triggers.forEach(function (trigger) {
      var id = trigger.getAttribute("data-dropdown-toggle");
      var dropdown = document.getElementById(id);
      if (!dropdown) return;

      trigger.addEventListener("click", function (e) {
        e.stopPropagation();
        var willOpen = dropdown.classList.contains("hidden");
        var backdrops = document.querySelectorAll('[data-dropdown-close="' + id + '"]');
        closeDropdowns(id);
        dropdown.classList.toggle("hidden", !willOpen);
        trigger.setAttribute("aria-expanded", willOpen ? "true" : "false");
        backdrops.forEach(function (backdrop) {
          if (backdrop.classList.contains("repo-upload-backdrop")) {
            backdrop.classList.toggle("hidden", !willOpen);
          }
        });
      });

      dropdown.addEventListener("click", function (e) {
        e.stopPropagation();
      });
    });

    document.querySelectorAll("[data-dropdown-close]").forEach(function (closer) {
      closer.addEventListener("click", function (e) {
        e.stopPropagation();
        var id = closer.getAttribute("data-dropdown-close");
        var dropdown = document.getElementById(id);
        var trigger = document.querySelector('[data-dropdown-toggle="' + id + '"]');
        var backdrops = document.querySelectorAll('[data-dropdown-close="' + id + '"]');

        if (dropdown) dropdown.classList.add("hidden");
        if (trigger) trigger.setAttribute("aria-expanded", "false");
        backdrops.forEach(function (backdrop) {
          if (backdrop.classList.contains("repo-upload-backdrop")) {
            backdrop.classList.add("hidden");
          }
        });
      });
    });

    document.addEventListener("click", function () {
      closeDropdowns();
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") closeDropdowns();
    });
  })();

  // Custom download confirmation modal for archive rows
  (function () {
    var modal = document.getElementById("downloadArchiveModal");
    if (!modal) return;

    var message = document.getElementById("downloadArchiveMessage");
    var cancel = document.getElementById("downloadArchiveCancel");
    var confirm = document.getElementById("downloadArchiveConfirm");
    var backdrop = modal.querySelector(".modal-backdrop");

    function closeModal(resetHref) {
      modal.classList.add("hidden");
      if (resetHref !== false && confirm) confirm.setAttribute("href", "#");
    }

    document.addEventListener("click", function (e) {
      var link = e.target.closest("[data-download-link]");
      if (!link) return;

      e.preventDefault();
      var archiveName = link.getAttribute("data-download-name") || "-";
      var archiveLocation = link.getAttribute("data-download-location") || "-";
      var archiveDate = link.getAttribute("data-download-date") || "-";
      var archiveOwner = link.getAttribute("data-download-uploader") || "-";
      if (message) {
        message.textContent =
          "Klaster Plot " +
          archiveName +
          " - " +
          archiveLocation +
          " (" +
          archiveDate +
          ", pemilik: " +
          archiveOwner +
          ") akan diunduh?";
      }
      if (confirm) {
        confirm.setAttribute("href", link.getAttribute("href") || "#");
      }
      modal.classList.remove("hidden");
      if (cancel) cancel.focus();
    });

    if (cancel) {
      cancel.addEventListener("click", function () {
        closeModal();
      });
    }
    if (backdrop) {
      backdrop.addEventListener("click", function () {
        closeModal();
      });
    }
    if (confirm) {
      confirm.addEventListener("click", function () {
        closeModal(false);
      });
    }

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        closeModal();
      }
    });
  })();

  // Toggle password visibility
  (function () {
    document.addEventListener("click", function (e) {
      var button = e.target.closest("[data-password-toggle]");
      if (!button) return;

      e.preventDefault();
      e.stopPropagation();

      var field = button.closest(".password-field");
      var input = field ? field.querySelector("input") : null;
      if (!input) return;

      var willShow = input.type === "password";
      input.type = willShow ? "text" : "password";
      button.textContent = willShow ? "Sembunyi" : "Lihat";
      button.setAttribute("aria-label", willShow ? "Sembunyikan password" : "Tampilkan password");
    }, true);
  })();

  // Explicit date picker trigger for mobile browsers
  (function () {
    document.addEventListener("click", function (e) {
      var button = e.target.closest("[data-date-picker-trigger]");
      if (!button) return;

      e.preventDefault();
      var target = button.getAttribute("data-date-picker-trigger");
      var input = target ? document.getElementById(target) : null;
      if (!input) return;

      input.focus();

      if (typeof input.showPicker === "function") {
        input.showPicker();
        return;
      }

      input.click();
    });
  })();

  // Owner profile popovers open only on click
  (function () {
    if (!document.querySelector(".uploader-popover")) return;

    function closePopovers(except) {
      document.querySelectorAll(".uploader-popover.open").forEach(function (popover) {
        if (popover !== except) {
          popover.classList.remove("open");
        }
      });
    }

    function handlePopoverPointer(e) {
      var trigger = e.target.closest(".uploader-trigger");
      var closeButton = e.target.closest(".uploader-close");
      var card = e.target.closest(".uploader-card");
      var popover = e.target.closest(".uploader-popover");

      if (closeButton) {
        e.preventDefault();
        e.stopPropagation();
        closePopovers();
        return;
      }

      if (trigger) {
        e.preventDefault();
        e.stopPropagation();
        var targetPopover = trigger.closest(".uploader-popover");
        if (!targetPopover) return;

        var willOpen = !targetPopover.classList.contains("open");
        closePopovers(targetPopover);
        targetPopover.classList.toggle("open", willOpen);
        return;
      }

      if (card || popover) {
        e.stopPropagation();
        return;
      }

      closePopovers();
    }

    document.addEventListener("pointerdown", handlePopoverPointer, true);

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") {
        closePopovers();
      }
    });
  })();

  // Confirm skipping email verification
  (function () {
    var modal = document.getElementById("skipVerificationModal");
    var openButton = document.querySelector("[data-skip-verification-open]");
    if (!modal || !openButton) return;

    var cancel = document.getElementById("skipVerificationCancel");
    var backdrop = modal.querySelector(".modal-backdrop");

    function closeModal() {
      modal.classList.add("hidden");
    }

    openButton.addEventListener("click", function () {
      modal.classList.remove("hidden");
      if (cancel) cancel.focus();
    });

    if (cancel) cancel.addEventListener("click", closeModal);
    if (backdrop) backdrop.addEventListener("click", closeModal);

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        closeModal();
      }
    });
  })();

  // Custom delete confirmation modal for archive groups
  (function () {
    var modal = document.getElementById("deleteArchiveGroupModal");
    if (!modal) return;

    var pendingForm = null;
    var message = document.getElementById("deleteArchiveGroupMessage");
    var cancel = document.getElementById("deleteArchiveGroupCancel");
    var confirm = document.getElementById("deleteArchiveGroupConfirm");
    var backdrop = modal.querySelector(".modal-backdrop");

    function openModal(form) {
      pendingForm = form;
      var archiveName = form.getAttribute("data-delete-group-name") || "-";
      var fileCount = form.getAttribute("data-delete-group-files") || "0";

      if (message) {
        message.textContent =
          'Arsip "' +
          archiveName +
          '" beserta ' +
          fileCount +
          " file di dalamnya akan dihapus dari repositori?";
      }

      modal.classList.remove("hidden");
      if (cancel) cancel.focus();
    }

    function closeModal() {
      pendingForm = null;
      modal.classList.add("hidden");
    }

    document.addEventListener("submit", function (e) {
      var form = e.target;
      if (!form || !form.matches("[data-delete-group-form]")) return;

      e.preventDefault();
      openModal(form);
    });

    if (cancel) cancel.addEventListener("click", closeModal);
    if (backdrop) backdrop.addEventListener("click", closeModal);
    if (confirm) {
      confirm.addEventListener("click", function () {
        if (pendingForm) {
          pendingForm.submit();
        }
      });
    }

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        closeModal();
      }
    });
  })();

  // Custom delete confirmation modal for archive rows
  (function () {
    var modal = document.getElementById("deleteArchiveModal");
    if (!modal) return;

    var pendingForm = null;
    var message = document.getElementById("deleteArchiveMessage");
    var cancel = document.getElementById("deleteArchiveCancel");
    var confirm = document.getElementById("deleteArchiveConfirm");
    var backdrop = modal.querySelector(".modal-backdrop");

    function openModal(form) {
      pendingForm = form;
      var archiveName = form.getAttribute("data-delete-name") || "-";
      var archiveLocation = form.getAttribute("data-delete-location") || "-";
      var archiveDate = form.getAttribute("data-delete-date") || "-";
      var archiveOwner = form.getAttribute("data-delete-uploader") || "-";
      if (message) {
        message.textContent =
          "Klaster Plot " +
          archiveName +
          " - " +
          archiveLocation +
          " (" +
          archiveDate +
          ", pemilik: " +
          archiveOwner +
          ") akan dihapus dari repositori?";
      }
      modal.classList.remove("hidden");
      if (cancel) cancel.focus();
    }

    function closeModal() {
      pendingForm = null;
      modal.classList.add("hidden");
    }

    document.addEventListener("submit", function (e) {
      var form = e.target;
      if (!form || !form.matches("[data-delete-form]")) return;

      e.preventDefault();
      openModal(form);
    });

    if (cancel) cancel.addEventListener("click", closeModal);
    if (backdrop) backdrop.addEventListener("click", closeModal);
    if (confirm) {
      confirm.addEventListener("click", function () {
        if (pendingForm) {
          pendingForm.submit();
        }
      });
    }

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        closeModal();
      }
    });
  })();

  // Repeatable public contacts on profile page
  (function () {
    var list = document.querySelector("[data-public-contact-list]");
    var addButton = document.querySelector("[data-public-contact-add]");
    if (!list || !addButton) return;

    var maxContacts = 3;

    function getItems() {
      return Array.prototype.slice.call(list.querySelectorAll("[data-public-contact-item]"));
    }

    function updateControls() {
      var items = getItems();
      addButton.classList.toggle("hidden", items.length >= maxContacts);

      items.forEach(function (item) {
        var removeButton = item.querySelector("[data-public-contact-remove]");
        if (removeButton) {
          removeButton.classList.toggle("hidden", items.length <= 1);
        }
      });
    }

    function createItem() {
      var item = document.createElement("div");
      item.className = "public-contact-item";
      item.setAttribute("data-public-contact-item", "");

      var input = document.createElement("input");
      input.name = "public_contact[]";
      input.type = "text";
      input.maxLength = 150;
      input.placeholder = "Email, no telp, WhatsApp, atau kontak lain";

      var removeButton = document.createElement("button");
      removeButton.className = "icon-action danger";
      removeButton.type = "button";
      removeButton.textContent = "x";
      removeButton.setAttribute("data-public-contact-remove", "");
      removeButton.setAttribute("aria-label", "Hapus kontak");

      item.appendChild(input);
      item.appendChild(removeButton);

      return item;
    }

    addButton.addEventListener("click", function () {
      if (getItems().length >= maxContacts) return;

      var item = createItem();
      list.appendChild(item);
      updateControls();

      var input = item.querySelector("input");
      if (input) input.focus();
    });

    list.addEventListener("click", function (e) {
      var removeButton = e.target.closest("[data-public-contact-remove]");
      if (!removeButton) return;

      var item = removeButton.closest("[data-public-contact-item]");
      var items = getItems();
      if (!item) return;

      if (items.length <= 1) {
        var input = item.querySelector("input");
        if (input) input.value = "";
        return;
      }

      item.remove();
      updateControls();
    });

    updateControls();
  })();

  // Cluster code input: uppercase, no spaces, max 8 chars
  (function () {
    var input = document.querySelector("[data-cluster-code-input]");
    if (!input) return;

    function normalizeClusterCode() {
      var nextValue = input.value
        .toUpperCase()
        .replace(/\s+/g, "")
        .replace(/[^A-Z0-9]/g, "")
        .slice(0, 8);

      if (input.value !== nextValue) {
        input.value = nextValue;
      }
    }

    input.addEventListener("input", normalizeClusterCode);
    input.addEventListener("paste", function () {
      setTimeout(normalizeClusterCode, 0);
    });
    input.addEventListener("keydown", function (e) {
      if (e.key === " ") {
        e.preventDefault();
      }
    });
  })();

  // Location suggestions for repository upload form
  (function () {
    var input = document.querySelector("[data-location-input]");
    var menu = document.querySelector("[data-location-suggestions]");
    var source = document.querySelector("[data-location-source]");
    if (!input || !menu || !source) return;

    var maxItems = 10;
    var locations = Array.prototype.map.call(
      source.querySelectorAll("[data-location-value]"),
      function (item) {
        return item.getAttribute("data-location-value") || "";
      }
    ).filter(Boolean);

    function shuffle(items) {
      var copy = items.slice();
      for (var i = copy.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var tmp = copy[i];
        copy[i] = copy[j];
        copy[j] = tmp;
      }
      return copy;
    }

    function renderSuggestions() {
      var query = input.value.trim().toLowerCase();
      var matches = query
        ? locations.filter(function (location) {
            return location.toLowerCase().indexOf(query) !== -1;
          })
        : shuffle(locations);

      matches = matches.slice(0, maxItems);
      menu.innerHTML = "";

      if (matches.length === 0) {
        var empty = document.createElement("div");
        empty.className = "empty-state";
        empty.textContent = locations.length === 0
          ? "Belum ada saran kawasan."
          : 'Tambahkan lokasi "' + input.value.trim() + '"';
        menu.appendChild(empty);
        menu.classList.remove("hidden");
        return;
      }

      matches.forEach(function (location) {
        var button = document.createElement("button");
        button.type = "button";
        button.textContent = location;
        button.addEventListener("mousedown", function (e) {
          e.preventDefault();
          input.value = location;
          menu.classList.add("hidden");
        });
        menu.appendChild(button);
      });

      menu.classList.remove("hidden");
    }

    input.addEventListener("focus", renderSuggestions);
    input.addEventListener("click", renderSuggestions);
    input.addEventListener("input", renderSuggestions);
    input.addEventListener("keydown", function (e) {
      if (e.key === "Escape") menu.classList.add("hidden");
    });

    document.addEventListener("click", function (e) {
      if (!menu.contains(e.target) && e.target !== input) {
        menu.classList.add("hidden");
      }
    });
  })();

  // Local tabs for the repository page
  (function () {
    var tabs = document.querySelectorAll("[data-tab-target]");
    if (!tabs || tabs.length === 0) return;

    tabs.forEach(function (tab) {
      tab.addEventListener("click", function () {
        var targetId = tab.getAttribute("data-tab-target");
        var targetPanel = document.getElementById(targetId);
        if (!targetPanel) return;

        tabs.forEach(function (item) {
          var panel = document.getElementById(item.getAttribute("data-tab-target"));
          item.classList.toggle("active", item === tab);
          item.setAttribute("aria-selected", item === tab ? "true" : "false");
          if (panel) panel.classList.toggle("hidden", item !== tab);
        });

        if (typeof window.adjustRepoDataTables === "function") {
          window.setTimeout(window.adjustRepoDataTables, 0);
        }
      });
    });
  })();

  // DataTables enhancement for repository tables
  (function () {
    var attempts = 0;
    var maxAttempts = 20;
    var repoDataTables = [];
    var publicArchiveDataTable = null;
    var hasFilesFilter = document.querySelector("[data-public-has-files-filter]");
    var publicArchiveFilterRegistered = false;
    var hasFilesFilterStorageKey = "azimutreePublicHasFilesOnly";

    if (hasFilesFilter) {
      try {
        var storedHasFilesFilter = window.localStorage.getItem(hasFilesFilterStorageKey);
        hasFilesFilter.checked = storedHasFilesFilter === null ? true : storedHasFilesFilter === "1";
      } catch (err) {
        hasFilesFilter.checked = true;
      }
    }

    window.adjustRepoDataTables = function () {
      repoDataTables.forEach(function (table) {
        if (table && table.columns && typeof table.columns.adjust === "function") {
          table.columns.adjust();
        }
      });
    };

    function initDataTables() {
      if (typeof DataTable === "undefined") {
        attempts += 1;
        if (attempts < maxAttempts) {
          setTimeout(initDataTables, 100);
        }
        return;
      }

      if (
        hasFilesFilter &&
        !publicArchiveFilterRegistered &&
        window.DataTable.ext &&
        window.DataTable.ext.search
      ) {
        window.DataTable.ext.search.push(function (settings, data) {
          if (!hasFilesFilter.checked || !settings.nTable || settings.nTable.id !== "publicArchiveTable") {
            return true;
          }

          var fileCount = parseInt(data[2], 10);

          return !Number.isNaN(fileCount) && fileCount > 0;
        });
        publicArchiveFilterRegistered = true;
      }

      var commonOptions = {
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        order: [[3, "desc"]],
        language: {
          search: "Cari:",
          lengthMenu: "Tampilkan _MENU_ data",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          infoEmpty: "Belum ada data",
          infoFiltered: "(difilter dari _MAX_ total data)",
          zeroRecords: "Data tidak ditemukan",
          emptyTable: "Belum ada file arsip.",
          paginate: {
            first: "<<",
            previous: "<",
            next: ">",
            last: ">>",
          },
        },
        columnDefs: [
          { orderable: false, targets: -1 },
        ],
      };

      var publicTable = document.getElementById("publicArchiveTable");
      if (publicTable && !publicTable.dataset.dtReady) {
        publicArchiveDataTable = new DataTable(publicTable, commonOptions);
        repoDataTables.push(publicArchiveDataTable);
        publicTable.dataset.dtReady = "true";
      }

      var ownedTable = document.getElementById("ownedArchiveTable");
      if (ownedTable && !ownedTable.dataset.dtReady) {
        repoDataTables.push(new DataTable(
          ownedTable,
          Object.assign({}, commonOptions, {
            language: Object.assign({}, commonOptions.language, {
              emptyTable: "Anda belum mengupload file arsip.",
            }),
          })
        ));
        ownedTable.dataset.dtReady = "true";
      }

      var fileTable = document.getElementById("fileArchiveTable");
      if (fileTable && !fileTable.dataset.dtReady) {
        repoDataTables.push(new DataTable(fileTable, commonOptions));
        fileTable.dataset.dtReady = "true";
      }

      var adminUserTable = document.getElementById("adminUserTable");
      if (adminUserTable && !adminUserTable.dataset.dtReady) {
        repoDataTables.push(new DataTable(
          adminUserTable,
          Object.assign({}, commonOptions, {
            order: [[0, "asc"]],
            language: Object.assign({}, commonOptions.language, {
              emptyTable: "Belum ada user.",
            }),
          })
        ));
        adminUserTable.dataset.dtReady = "true";
      }

      var adminArchiveTable = document.getElementById("adminArchiveTable");
      if (adminArchiveTable && !adminArchiveTable.dataset.dtReady) {
        repoDataTables.push(new DataTable(
          adminArchiveTable,
          Object.assign({}, commonOptions, {
            language: Object.assign({}, commonOptions.language, {
              emptyTable: "Belum ada arsip.",
            }),
          })
        ));
        adminArchiveTable.dataset.dtReady = "true";
      }

      window.setTimeout(window.adjustRepoDataTables, 0);
    }

    initDataTables();

    if (hasFilesFilter) {
      hasFilesFilter.addEventListener("change", function () {
        try {
          window.localStorage.setItem(hasFilesFilterStorageKey, hasFilesFilter.checked ? "1" : "0");
        } catch (err) {}

        if (!publicArchiveDataTable) return;

        publicArchiveDataTable.draw();
      });
    }
  })();

  // Press/tap animation for screenshot images
  (function () {
    var imgs = document.querySelectorAll(".screenshots img");
    if (!imgs || imgs.length === 0) return;

    imgs.forEach(function (img) {
      // make images focusable for keyboard activation
      if (!img.hasAttribute("tabindex")) img.setAttribute("tabindex", "0");

      function addPressed() {
        img.classList.add("pressed");
      }

      function removePressed() {
        img.classList.remove("pressed");
      }

      img.addEventListener("pointerdown", function (e) {
        addPressed();
      });

      img.addEventListener("pointerup", function (e) {
        removePressed();
      });

      img.addEventListener("pointercancel", removePressed);
      img.addEventListener("pointerleave", removePressed);

      // keyboard activation (Enter / Space)
      img.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          addPressed();
          setTimeout(removePressed, 150);
        }
      });
    });
  })();

  // Download confirmation modal
  var downloadBtn = document.getElementById("download");
  var dlModal = document.getElementById("downloadModal");
  if (downloadBtn && dlModal) {
    var dlConfirm = document.getElementById("downloadConfirm");
    var dlCancel = document.getElementById("downloadCancel");

    downloadBtn.addEventListener("click", function (e) {
      // prevent immediate navigation
      e.preventDefault();
      // Read the release version independently of the mirror redirect URL.
      var versionEl = document.getElementById("downloadVersion");
      var version = downloadBtn.getAttribute("data-version");
      var match = (downloadBtn.getAttribute("href") || "").match(/v(\d+\.\d+\.\d+)/);
      if (!version && match) version = match[1];
      if (versionEl) {
        versionEl.textContent = version ? "Versi APK unduhan: " + version : "";
      }
      // show modal
      dlModal.classList.remove("hidden");
      // focus confirm
      dlConfirm.focus();
    });

    function closeDlModal() {
      dlModal.classList.add("hidden");
    }

    dlCancel.addEventListener("click", function () {
      closeDlModal();
    });

    dlConfirm.addEventListener("click", function () {
      // proceed to download in a new tab/window (preserve current page)
      var url = downloadBtn.getAttribute("href");
      if (url) {
        var w = window.open(url, "_blank");
        try {
          if (w) w.opener = null;
        } catch (e) {
          /* ignore */
        }
      }
      closeDlModal();
    });

    // close when clicking outside modal-box
    dlModal.addEventListener("click", function (e) {
      if (e.target.classList && e.target.classList.contains("modal-backdrop")) {
        closeDlModal();
      }
    });

    // close on ESC
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !dlModal.classList.contains("hidden")) {
        closeDlModal();
      }
    });
  }

  // Screenshot slider
  var slider = document.querySelector(".screenshots");
  var dots = document.querySelectorAll(".dot");

  if (slider && dots.length > 0 && slider.children.length > 0) {
    // Helper function to calculate slide width
    function getSlideWidth() {
      var itemWidth = slider.children[0].offsetWidth;
      var gap = parseFloat(getComputedStyle(slider).gap) || 0;
      return itemWidth + gap;
    }

    // Update active dot on scroll
    slider.addEventListener("scroll", function () {
      var scrollLeft = slider.scrollLeft;
      var slideWidth = getSlideWidth();
      var currentIndex = Math.round(scrollLeft / slideWidth);

      dots.forEach(function (dot, index) {
        if (index === currentIndex) {
          dot.classList.add("active");
        } else {
          dot.classList.remove("active");
        }
      });
    });

    // Click on dots to navigate
    dots.forEach(function (dot) {
      dot.addEventListener("click", function () {
        var slideIndex = parseInt(this.getAttribute("data-slide"));
        var slideWidth = getSlideWidth();
        slider.scrollTo({
          left: slideIndex * slideWidth,
          behavior: "smooth",
        });
      });
    });
  }

  // Hero-style fullscreen transition (tap to zoom) for screenshots
  (function () {
    var imgs = document.querySelectorAll(".screenshots img");
    if (!imgs || imgs.length === 0) return;

    function openHero(img) {
      var rect = img.getBoundingClientRect();
      var clone = img.cloneNode(true);
      clone.style.position = "fixed";
      clone.style.left = rect.left + "px";
      clone.style.top = rect.top + "px";
      clone.style.width = rect.width + "px";
      clone.style.height = rect.height + "px";
      clone.style.margin = "0";
      clone.style.zIndex = 200;
      clone.classList.add("hero-image");
      document.body.appendChild(clone);

      // small overlay backdrop
      var overlay = document.createElement("div");
      overlay.className = "hero-overlay";
      document.body.appendChild(overlay);

      // ensure layout applied
      clone.getBoundingClientRect();

      // compute target size (fit into viewport while keeping aspect)
      var maxW = Math.max(window.innerWidth * 0.92, window.innerWidth - 32);
      var maxH = Math.max(window.innerHeight * 0.86, window.innerHeight - 32);
      var naturalW = img.naturalWidth || rect.width;
      var naturalH = img.naturalHeight || rect.height;
      var ratio = naturalW / Math.max(1, naturalH);
      var targetW = maxW;
      var targetH = targetW / ratio;
      if (targetH > maxH) {
        targetH = maxH;
        targetW = targetH * ratio;
      }
      var targetLeft = (window.innerWidth - targetW) / 2;
      var targetTop = (window.innerHeight - targetH) / 2;

      // animate to center
      requestAnimationFrame(function () {
        overlay.classList.add("open");
        clone.style.left = targetLeft + "px";
        clone.style.top = targetTop + "px";
        clone.style.width = targetW + "px";
        clone.style.height = targetH + "px";
        clone.style.borderRadius = "12px";
      });

      function closeHero() {
        overlay.classList.remove("open");
        clone.style.left = rect.left + "px";
        clone.style.top = rect.top + "px";
        clone.style.width = rect.width + "px";
        clone.style.height = rect.height + "px";
        setTimeout(function () {
          if (clone.parentNode) clone.parentNode.removeChild(clone);
          if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
        }, 360);
        document.removeEventListener("keydown", onKey);
      }

      overlay.addEventListener("click", closeHero);
      clone.addEventListener("click", closeHero);

      function onKey(e) {
        if (e.key === "Escape") closeHero();
      }
      document.addEventListener("keydown", onKey);
    }

    imgs.forEach(function (el) {
      el.style.cursor = "zoom-in";
      el.addEventListener("click", function () {
        openHero(el);
      });
      el.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          openHero(el);
        }
      });
    });
  })();
  // Translate vertical mouse wheel into horizontal scroll for desktop users
  var sliderContainer = document.querySelector(".slider-container");
  var scrollEl = slider || sliderContainer;
  if (scrollEl) {
    // wheel -> horizontal (for mouse wheel users)
    scrollEl.addEventListener(
      "wheel",
      function (e) {
        if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
          e.preventDefault();
          scrollEl.scrollLeft += e.deltaY;
        }
      },
      { passive: false },
    );

    // drag-to-scroll (click and drag) for desktop
    var isDown = false;
    var startX;
    var scrollStart;

    scrollEl.addEventListener("pointerdown", function (e) {
      isDown = true;
      startX = e.clientX;
      scrollStart = scrollEl.scrollLeft;
      scrollEl.style.cursor = "grabbing";
      e.preventDefault();
    });

    document.addEventListener("pointerup", function () {
      if (isDown) {
        isDown = false;
        scrollEl.style.cursor = "";
      }
    });

    document.addEventListener("pointermove", function (e) {
      if (!isDown) return;
      var dx = e.clientX - startX;
      scrollEl.scrollLeft = scrollStart - dx;
    });
  }
});
