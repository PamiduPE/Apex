/* Sends the buildcv.html form to submit.php (saves to MySQL).
   Runs before the old WhatsApp handler and replaces it. */
(() => {
  'use strict';

  const form = document.getElementById('cvForm');
  if (!form) return;

  const btn = document.getElementById('submitBtn');
  const errBox = document.getElementById('formError');
  const pkgGrid = document.getElementById('pkgGrid');
  const btnHtml = btn.innerHTML;

  const showError = (msg) => {
    errBox.textContent = msg;
    errBox.classList.add('show');
  };

  /* Shrink big phone photos (max 1600px, JPEG) so uploads are fast and don't fail */
  const compress = (file) => new Promise((resolve) => {
    if (!file.type.startsWith('image/') || file.size < 400 * 1024) return resolve(file);
    const url = URL.createObjectURL(file);
    const img = new Image();
    img.onload = () => {
      const scale = Math.min(1, 1600 / Math.max(img.width, img.height));
      const c = document.createElement('canvas');
      c.width = Math.round(img.width * scale);
      c.height = Math.round(img.height * scale);
      const ctx = c.getContext('2d');
      ctx.fillStyle = '#fff';
      ctx.fillRect(0, 0, c.width, c.height);
      ctx.drawImage(img, 0, 0, c.width, c.height);
      c.toBlob((blob) => {
        URL.revokeObjectURL(url);
        resolve(blob ? new File([blob], 'photo.jpg', { type: 'image/jpeg' }) : file);
      }, 'image/jpeg', 0.85);
    };
    img.onerror = () => { URL.revokeObjectURL(url); resolve(file); };
    img.src = url;
  });

  const showSuccess = (name) => {
    document.querySelector('.progress-wrap')?.remove();
    const card = form.querySelector('.form-card');
    card.innerHTML =
      '<div class="section-block submit-block" style="padding:56px 24px">' +
        '<span class="section-ic" style="width:64px;height:64px;margin:0 auto 18px">' +
          '<svg style="width:30px;height:30px" aria-hidden="true"><use href="#i-check"/></svg></span>' +
        '<h2 style="font-size:1.6rem;color:var(--ink);margin-bottom:10px">Details received</h2>' +
        '<p class="hint" style="margin-bottom:24px">Thank you, <strong id="okName"></strong>. ' +
        'Our writers will review your details and contact you shortly to confirm your CV.</p>' +
        '<a class="btn btn-plum" href="https://wa.me/94721223139" target="_blank" rel="noopener">Message us on WhatsApp</a>' +
      '</div>';
    document.getElementById('okName').textContent = name;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  /* capture on document => fires before the old WhatsApp submit handler */
  document.addEventListener('submit', async (e) => {
    if (e.target !== form) return;
    e.preventDefault();
    e.stopImmediatePropagation();

    /* validate */
    let ok = true;
    form.querySelectorAll('[required]').forEach((f) => {
      const bad = f.value.trim() === '';
      f.style.borderColor = bad ? 'var(--red)' : '';
      if (bad) ok = false;
    });
    const pkg = form.querySelector('input[name="package"]:checked');
    pkgGrid.style.outline = pkg ? '' : '2px solid var(--red)';
    pkgGrid.style.outlineOffset = pkg ? '' : '6px';
    pkgGrid.style.borderRadius = pkg ? '' : '20px';

    if (!ok || !pkg) {
      showError(!pkg && !ok ? 'Please fill in all required fields marked with * and choose a package.'
        : !pkg ? 'Please choose a package before submitting.'
        : 'Please fill in all required fields marked with *.');
      const firstBad = [...form.querySelectorAll('[required]')].find((f) => f.value.trim() === '');
      (firstBad || pkgGrid).scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }
    errBox.classList.remove('show');

    /* send */
    btn.disabled = true;
    btn.textContent = 'Sending…';
    try {
      const fd = new FormData(form);
      const photo = fd.get('photo');
      if (photo && photo.size) {
        const out = await compress(photo);
        fd.set('photo', out, out.name || 'photo.jpg');
      }
      const res = await fetch('submit.php', { method: 'POST', body: fd });
      let data = null;
      try { data = await res.json(); } catch (_) {}
      if (!res.ok || !data || !data.ok) {
        throw new Error((data && data.error) || 'Something went wrong. Please try again.');
      }
      showSuccess(fd.get('fullName'));
    } catch (err) {
      showError(err.message || 'Could not send. Check your connection and try again.');
      btn.disabled = false;
      btn.innerHTML = btnHtml;
    }
  }, true);
})();