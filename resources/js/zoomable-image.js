// resources/js/zoomable-image.js
// Works with Filament + Livewire v3 (delegated listeners + re-init on navigation)

(() => {
  const DEFAULTS = { min: 0.5, max: 4, step: 0.25, animMs: 150 };
  const STATES = new Map(); // scopeId -> { img, container, scale, tx, ty, ... }

  const clamp = (v, min, max) => Math.min(max, Math.max(min, v));

  function applyTransform(img, s, animate = false) {
    if (animate) {
      img.style.transition = `transform ${DEFAULTS.animMs}ms ease-out`;
      clearTimeout(img._zoomTid);
      img._zoomTid = setTimeout(() => (img.style.transition = 'none'), DEFAULTS.animMs);
    } else {
      img.style.transition = 'none';
    }
    img.style.transform = `translate(${s.tx}px, ${s.ty}px) scale(${s.scale})`;
  }

  function ensureScope(scope) {
    const container = document.querySelector(`[data-zoom-scope="${scope}"]`);
    const img = document.querySelector(`[data-zoom-img="${scope}"]`);
    if (!container || !img) return null;

    let s = STATES.get(scope);
    if (!s) {
      s = { scope, container, img, scale: 1, tx: 0, ty: 0, pan: false, sx: 0, sy: 0 };
      STATES.set(scope, s);
      // Bind pan / wheel / dblclick once per container
      bindViewportHandlers(s);
      applyTransform(img, s, false);
    } else {
      // If DOM re-rendered, refresh references
      s.container = container;
      s.img = img;
      if (!container._zoomBound) bindViewportHandlers(s);
      applyTransform(img, s, false);
    }
    return s;
  }

  function bindViewportHandlers(s) {
    const { container } = s;
    container.addEventListener('mousedown', (e) => {
      s.pan = true;
      s.sx = e.clientX - s.tx;
      s.sy = e.clientY - s.ty;
    });
    container.addEventListener('mousemove', (e) => {
      if (!s.pan) return;
      s.tx = e.clientX - s.sx;
      s.ty = e.clientY - s.sy;
      applyTransform(s.img, s, false);
    });
    ['mouseup', 'mouseleave'].forEach((ev) =>
      container.addEventListener(ev, () => (s.pan = false)),
    );

    // Wheel zoom (cursor-centered)
    container.addEventListener(
      'wheel',
      (e) => {
        e.preventDefault();
        const old = s.scale;
        const delta = e.deltaY < 0 ? DEFAULTS.step : -DEFAULTS.step;
        const next = clamp(Number((s.scale + delta).toFixed(2)), DEFAULTS.min, DEFAULTS.max);
        if (next === old) return;

        const rect = container.getBoundingClientRect();
        const cx = e.clientX - rect.left - rect.width / 2;
        const cy = e.clientY - rect.top - rect.height / 2;
        s.tx -= cx * (next - old);
        s.ty -= cy * (next - old);

        s.scale = next;
        applyTransform(s.img, s, false);
      },
      { passive: false },
    );

    // Double click zoom in
    container.addEventListener('dblclick', (e) => {
      e.preventDefault();
      s.scale = clamp(Number((s.scale + DEFAULTS.step).toFixed(2)), DEFAULTS.min, DEFAULTS.max);
      applyTransform(s.img, s, true);
    });

    container._zoomBound = true;
  }

  function initAllScopes() {
    document.querySelectorAll('[data-zoom-scope]').forEach((el) => {
      ensureScope(el.getAttribute('data-zoom-scope'));
    });
  }

  // Delegated click handler for Page-header buttons created with GDF
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-zoom-action][data-zoom-scope]');
    if (!btn) return;

    const scope = btn.getAttribute('data-zoom-scope');
    const s = ensureScope(scope);
    if (!s) return;

    const action = btn.getAttribute('data-zoom-action');
    if (action === 'in') {
      s.scale = clamp(Number((s.scale + DEFAULTS.step).toFixed(2)), DEFAULTS.min, DEFAULTS.max);
      applyTransform(s.img, s, true);
    } else if (action === 'out') {
      s.scale = clamp(Number((s.scale - DEFAULTS.step).toFixed(2)), DEFAULTS.min, DEFAULTS.max);
      applyTransform(s.img, s, true);
    } else if (action === 'reset') {
      s.scale = 1;
      s.tx = 0;
      s.ty = 0;
      applyTransform(s.img, s, true);
    }
  });

  // Initial run + re-run on Livewire navigations
  const boot = () => initAllScopes();

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  // Livewire v3 events in Filament 3
  document.addEventListener('livewire:load', boot);
  document.addEventListener('livewire:navigated', boot);
})();