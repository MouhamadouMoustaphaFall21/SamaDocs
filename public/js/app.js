document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initSidebar();
  initDropdowns();
  initModals();
  initUploadZone();
  initViewToggle();
  initDocumentSearch();
  initLandingNav();
  initMobileNav();
  initPasswordStrength();
  initSettingsNav();
});

/* ============================================
   THEME
   ============================================ */
function initTheme() {
  const saved = localStorage.getItem('samadocs-theme') || 'light';
  document.documentElement.setAttribute('data-theme', saved);
  updateThemeIcons();

  document.querySelectorAll('[data-theme-toggle], .theme-toggle-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const current = document.documentElement.getAttribute('data-theme');
      const next = current === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      localStorage.setItem('samadocs-theme', next);
      updateThemeIcons();
    });
  });
}

function updateThemeIcons() {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  document.querySelectorAll('[data-theme-toggle] i, .theme-toggle-btn i').forEach(icon => {
    icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
  });
}

/* ============================================
   SIDEBAR
   ============================================ */
function initSidebar() {
  const hamburger = document.querySelector('.hamburger');
  const sidebar = document.querySelector('.sidebar');
  const overlay = document.querySelector('.sidebar-overlay');

  if (!hamburger || !sidebar) return;

  hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay?.classList.toggle('active');
    document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
  });

  overlay?.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  });

  // Close sidebar on link click (mobile)
  sidebar.querySelectorAll('.sidebar-nav-item').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768) {
        sidebar.classList.remove('open');
        overlay?.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  });
}

/* ============================================
   DROPDOWNS
   ============================================ */
function initDropdowns() {
  document.querySelectorAll('.dropdown').forEach(dd => {
    const trigger = dd.querySelector('[data-dropdown-trigger]');
    const menu = dd.querySelector('.dropdown-menu');
    if (!trigger || !menu) return;

    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      document.querySelectorAll('.dropdown-menu.active').forEach(m => {
        if (m !== menu) m.classList.remove('active');
      });
      menu.classList.toggle('active');
    });
  });

  document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-menu.active').forEach(m => m.classList.remove('active'));
  });
}

/* ============================================
   MODALS
   ============================================ */
function initModals() {
  document.querySelectorAll('[data-modal-trigger]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-modal-trigger');
      openModal(id);
    });
  });

  document.querySelectorAll('[data-modal-close]').forEach(btn => {
    btn.addEventListener('click', () => {
      const overlay = btn.closest('.modal-overlay');
      closeModal(overlay);
    });
  });

  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeModal(overlay);
    });
  });
}

function openModal(id) {
  const overlay = document.getElementById(id);
  if (overlay) {
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(overlay) {
  if (overlay) {
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }
}

/* ============================================
   UPLOAD ZONE (Drag & Drop)
   ============================================ */
function initUploadZone() {
  const zones = document.querySelectorAll('.upload-zone');
  zones.forEach(zone => {
    const input = zone.querySelector('input[type="file"]');

    zone.addEventListener('dragover', (e) => {
      e.preventDefault();
      zone.classList.add('dragover');
    });

    zone.addEventListener('dragleave', () => {
      zone.classList.remove('dragover');
    });

    zone.addEventListener('drop', (e) => {
      e.preventDefault();
      zone.classList.remove('dragover');
      if (input && e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        input.dispatchEvent(new Event('change'));
      }
    });

    zone.addEventListener('click', () => input?.click());

    if (input) {
      input.addEventListener('change', () => {
        if (input.files.length) {
          const name = input.files[0].name;
          const nameInput = zone.closest('.modal-body')?.querySelector('[name="document_name"]');
          if (nameInput) nameInput.value = name.replace(/\.[^.]+$/, '');
          showUploadPreview(input.files[0], zone);
        }
      });
    }
  });
}

function showUploadPreview(file, zone) {
  const existing = zone.closest('.modal-body')?.querySelector('.upload-preview');
  if (existing) existing.remove();

  const ext = file.name.split('.').pop().toLowerCase();
  const icons = { pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word', xls: 'fa-file-excel', xlsx: 'fa-file-excel', jpg: 'fa-file-image', jpeg: 'fa-file-image', png: 'fa-file-image' };
  const icon = icons[ext] || 'fa-file';

  const preview = document.createElement('div');
  preview.className = 'upload-preview';
  preview.style.cssText = 'display:flex;align-items:center;gap:12px;padding:12px 16px;background:var(--bg-secondary);border-radius:8px;margin-top:16px;border:1px solid var(--border-primary);';
  preview.innerHTML = `
    <i class="fas ${icon}" style="font-size:1.5rem;color:var(--primary-600);"></i>
    <div style="flex:1;">
      <div style="font-weight:500;font-size:0.875rem;">${file.name}</div>
      <div style="font-size:0.8125rem;color:var(--text-muted);">${formatFileSize(file.size)}</div>
    </div>
    <i class="fas fa-check-circle" style="color:var(--success-500);"></i>
  `;

  const uploadZone = zone.closest('.modal-body')?.querySelector('.upload-zone');
  uploadZone?.after(preview);
}

function formatFileSize(bytes) {
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / 1048576).toFixed(1) + ' MB';
}

/* ============================================
   VIEW TOGGLE (Grid / List)
   ============================================ */
function initViewToggle() {
  document.querySelectorAll('.view-toggle').forEach(toggle => {
    const buttons = toggle.querySelectorAll('button');
    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const view = btn.dataset.view;
        const container = toggle.dataset.target;
        if (!container) return;

        const el = document.getElementById(container);
        if (!el) return;

        const isGrid = view === 'grid-view';
        el.classList.toggle('hidden', !isGrid);

        // Toggle related list container if present
        const listEl = document.getElementById(container + '-list');
        if (listEl) listEl.classList.toggle('hidden', isGrid);
      });
    });
  });
}

/* ============================================
   DOCUMENT SEARCH
   ============================================ */
function initDocumentSearch() {
  const searchInput = document.querySelector('.doc-search-input');
  if (!searchInput) return;

  searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase();
    document.querySelectorAll('.doc-card, .doc-row').forEach(card => {
      const name = card.querySelector('.doc-card-name, .doc-row-name')?.textContent.toLowerCase() || '';
      const category = card.querySelector('.doc-card-category, .doc-row-category')?.textContent.toLowerCase() || '';
      card.style.display = (name.includes(query) || category.includes(query)) ? '' : 'none';
    });
  });
}

/* ============================================
   LANDING NAV SCROLL
   ============================================ */
function initLandingNav() {
  const nav = document.querySelector('.landing-nav');
  if (!nav) return;

  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 20);
  });
}

/* ============================================
   MOBILE NAV
   ============================================ */
function initMobileNav() {
  const mobileNavItems = document.querySelectorAll('.mobile-nav-item');
  mobileNavItems.forEach(item => {
    item.addEventListener('click', () => {
      mobileNavItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
    });
  });
}

/* ============================================
   PASSWORD STRENGTH
   ============================================ */
function initPasswordStrength() {
  const input = document.querySelector('#password');
  if (!input) return;

  input.addEventListener('input', () => {
    const val = input.value;
    const bars = document.querySelectorAll('.password-strength-bar');
    let strength = 0;

    if (val.length >= 8) strength++;
    if (/[A-Z]/.test(val)) strength++;
    if (/[0-9]/.test(val)) strength++;
    if (/[^A-Za-z0-9]/.test(val)) strength++;

    bars.forEach((bar, i) => {
      bar.className = 'password-strength-bar';
      if (i < strength) {
        if (strength <= 1) bar.classList.add('active');
        else if (strength <= 2) bar.classList.add('medium');
        else bar.classList.add('strong');
      }
    });
  });
}

/* ============================================
   SETTINGS NAV
   ============================================ */
function initSettingsNav() {
  document.querySelectorAll('.settings-nav-item').forEach(item => {
    item.addEventListener('click', () => {
      document.querySelectorAll('.settings-nav-item').forEach(i => i.classList.remove('active'));
      item.classList.add('active');

      const section = item.dataset.section;
      document.querySelectorAll('.settings-section-panel').forEach(p => p.classList.add('hidden'));
      const target = document.getElementById('settings-' + section);
      if (target) target.classList.remove('hidden');
    });
  });
}

/* ============================================
   TOAST NOTIFICATIONS
   ============================================ */
function showToast(type, message) {
  const container = document.querySelector('.toast-container') || createToastContainer();
  const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle', warning: 'fa-exclamation-triangle' };
  const iconColors = { success: 'var(--success-500)', error: 'var(--danger-500)', info: 'var(--primary-500)', warning: 'var(--warning-500)' };

  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `
    <i class="fas ${icons[type]}" style="color:${iconColors[type]};font-size:1.125rem;"></i>
    <span style="flex:1;font-size:0.875rem;">${message}</span>
    <button class="toast-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
  `;
  container.appendChild(toast);

  setTimeout(() => toast.remove(), 4000);
}

function createToastContainer() {
  const container = document.createElement('div');
  container.className = 'toast-container';
  document.body.appendChild(container);
  return container;
}

/* ============================================
   CONFIRM DELETE
   ============================================ */
function confirmDelete(message, callback) {
  const overlay = document.createElement('div');
  overlay.className = 'modal-overlay active';
  overlay.innerHTML = `
    <div class="modal" style="max-width:420px;">
      <div class="modal-header">
        <h3 class="heading-sm">Confirmer la suppression</h3>
        <button class="btn btn-icon btn-ghost" onclick="this.closest('.modal-overlay').remove();document.body.style.overflow='';"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <p style="color:var(--text-secondary);">${message}</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" onclick="this.closest('.modal-overlay').remove();document.body.style.overflow='';">Annuler</button>
        <button class="btn btn-danger" id="confirm-delete-btn">Supprimer</button>
      </div>
    </div>
  `;
  document.body.appendChild(overlay);
  document.body.style.overflow = 'hidden';

  overlay.querySelector('#confirm-delete-btn').addEventListener('click', () => {
    overlay.remove();
    document.body.style.overflow = '';
    if (callback) callback();
  });

  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
      overlay.remove();
      document.body.style.overflow = '';
    }
  });
}

// PWA - register service worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/sw.js').catch(function(err) {
      console.error('Service Worker registration failed:', err);
    });
  });
}
