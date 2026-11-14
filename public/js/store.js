document.querySelectorAll('[data-open]').forEach(button => {
 button.addEventListener('click',()=>{const dialog=document.getElementById(button.dataset.open);if(dialog){dialog.showModal();dialog.querySelector('input')?.focus();}});
});
document.querySelectorAll('dialog').forEach(dialog=>dialog.addEventListener('click',event=>{if(event.target===dialog){const rect=dialog.getBoundingClientRect();if(event.clientX<rect.left||event.clientX>rect.right||event.clientY<rect.top||event.clientY>rect.bottom)dialog.close();}}));
document.querySelectorAll('[data-dismiss]').forEach(button=>button.addEventListener('click',()=>button.closest('.toast').remove()));
document.querySelectorAll('[data-quantity]').forEach(button=>button.addEventListener('click',()=>{const input=button.closest('.quantity').querySelector('input');input.value=Math.max(Number(input.min)||1,Math.min(Number(input.max)||10,(Number(input.value)||1)+Number(button.dataset.quantity)));input.dispatchEvent(new Event('change',{bubbles:true}));}));
document.querySelectorAll('form').forEach(form=>form.addEventListener('submit',()=>{const button=form.querySelector('[data-submit]');if(button){button.disabled=true;button.setAttribute('aria-busy','true');}}));
window.addEventListener('pageshow',()=>document.querySelectorAll('[data-submit]').forEach(button=>{button.disabled=false;button.removeAttribute('aria-busy');}));

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const hero = document.querySelector('[data-home-hero]');
const header = document.querySelector('[data-store-header]');
const closeAnnouncement = document.querySelector('[data-close-announcement]');

function updateHeader() {
  if (hero && header) {
    header.classList.toggle('is-over-hero', hero.getBoundingClientRect().top >= 0 && (window.scrollY === 0 || header.getBoundingClientRect().top > 0));
  }
}

updateHeader();
window.addEventListener('scroll', updateHeader, { passive: true });
window.addEventListener('resize', updateHeader);
if (closeAnnouncement) {
  closeAnnouncement.hidden = false;
  closeAnnouncement.addEventListener('click', () => {
    closeAnnouncement.closest('.announcement').hidden = true;
    updateHeader();
  });
}

if ('IntersectionObserver' in window && !reducedMotion.matches) {
  const reveal = new IntersectionObserver(entries => {
    entries.forEach(({ target, isIntersecting }) => {
      if (isIntersecting) {
        target.classList.add('is-revealed');
        reveal.unobserve(target);
      }
    });
  });
  document.querySelectorAll('[data-reveal]').forEach(element => {
    if (element.getBoundingClientRect().top >= window.innerHeight) {
      element.classList.add('reveal-ready');
      reveal.observe(element);
    }
  });
}

document.querySelectorAll('[data-rail]').forEach(rail => {
  const controls = rail.parentElement.querySelector('[data-rail-controls]');
  const [previous, next] = controls.querySelectorAll('button');
  const direction = getComputedStyle(rail).direction === 'rtl' ? -1 : 1;

  function updateControls() {
    const maximum = rail.scrollWidth - rail.clientWidth;
    const offset = rail.scrollLeft * direction;
    controls.hidden = maximum < 2;
    previous.disabled = offset < 2;
    next.disabled = offset >= maximum - 2;
  }

  controls.addEventListener('click', event => {
    const step = event.target.closest('[data-rail-step]')?.dataset.railStep;
    if (!step) return;
    const gap = parseFloat(getComputedStyle(rail).columnGap) || 0;
    rail.scrollBy({
      left: Number(step) * direction * (rail.firstElementChild.getBoundingClientRect().width + gap),
      behavior: reducedMotion.matches ? 'instant' : 'smooth',
    });
  });
  rail.addEventListener('scroll', updateControls, { passive: true });
  if ('ResizeObserver' in window) new ResizeObserver(updateControls).observe(rail);
  updateControls();
});

document.querySelectorAll('[data-collection-tabs]').forEach(collection => {
  const nav = collection.querySelector('.collection-tabs');
  const tabs = Array.from(nav.querySelectorAll('a'));
  const panels = tabs.map(tab => document.getElementById(tab.hash.slice(1)));
  nav.setAttribute('role', 'tablist');

  function select(index, moveFocus = false) {
    tabs.forEach((tab, position) => {
      const active = position === index;
      tab.setAttribute('aria-selected', String(active));
      tab.tabIndex = active ? 0 : -1;
      panels[position].hidden = !active;
    });
    if (moveFocus) tabs[index].focus();
  }

  const direction = getComputedStyle(nav).direction === 'rtl' ? -1 : 1;
  tabs.forEach((tab, index) => {
    const panel = panels[index];
    tab.id = panel.id + '-tab';
    tab.setAttribute('role', 'tab');
    tab.setAttribute('aria-controls', panel.id);
    panel.setAttribute('role', 'tabpanel');
    panel.setAttribute('aria-labelledby', tab.id);
    tab.addEventListener('click', event => {
      event.preventDefault();
      select(index);
    });
    tab.addEventListener('keydown', event => {
      const targets = {
        ArrowRight: (index + direction + tabs.length) % tabs.length,
        ArrowLeft: (index - direction + tabs.length) % tabs.length,
        Home: 0,
        End: tabs.length - 1,
      };
      if (!Object.hasOwn(targets, event.key)) return;
      event.preventDefault();
      select(targets[event.key], true);
    });
  });
  select(Math.max(0, tabs.findIndex(tab => tab.hash === window.location.hash)));
});

document.querySelectorAll('[data-product-gallery]').forEach(gallery => {
  const thumbnails = gallery.querySelector('.gallery-thumbs');
  if (!thumbnails) return;
  const images = Array.from(thumbnails.querySelectorAll('button'));
  const stage = gallery.querySelector('[data-gallery-main]');
  const zoom = document.querySelector('[data-zoom-image]');
  const controls = gallery.querySelector('.gallery-controls');
  const [previous, next] = controls.querySelectorAll('button');
  let selected = 0;

  function select(index) {
    selected = Math.max(0, Math.min(images.length - 1, index));
    stage.src = zoom.src = images[selected].querySelector('img').src;
    images.forEach((button, index) => button.setAttribute('aria-pressed', String(index === selected)));
    previous.disabled = selected === 0;
    next.disabled = selected === images.length - 1;
  }

  images.forEach((button, index) => button.addEventListener('click', () => select(index)));
  previous.addEventListener('click', () => select(selected - 1));
  next.addEventListener('click', () => select(selected + 1));
  select(selected);
  thumbnails.hidden = false;
  controls.hidden = false;
});

// Footer link groups: open on desktop, collapsed accordions on phones
(() => {
  const groups = document.querySelectorAll('.footer-col');
  if (!groups.length || !window.matchMedia) return;
  const mobile = window.matchMedia('(max-width: 800px)');
  const sync = () => groups.forEach((group) => { group.open = !mobile.matches; });
  sync();
  mobile.addEventListener ? mobile.addEventListener('change', sync) : mobile.addListener(sync);
})();
