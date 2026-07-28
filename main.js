/* ApexCV — main.js */

// --- 1. Multi-Language System ---
const translations = {
    'en': {
        'nav-services': 'Services',
        'nav-designs': 'Our Designs',
        'nav-shoots': 'Our Shoots',
        'nav-packages': 'Packages',
        'nav-contact': 'Contact',
        'get-started': 'Get Started',
        'hero-badge': '2,700+ Careers Transformed',
        'hero-title': 'Land Your Dream Job with an <span class="highlight">ATS-Friendly</span> CV',
        'hero-sub': 'Expert storytelling meets recruitment technology. We make sure your CV passes the bots — and impresses the humans.',
        'cta-create': 'Create My CV →',
        'cta-pricing': 'View Pricing',
        'stat-cvs': 'CVs Written',
        'stat-ats': 'ATS Pass Rate',
        'stat-time': 'Response Time',
        'eyebrow-designs': 'Templates',
        'title-designs': 'Professional <span>Layouts</span>',
        'eyebrow-shoots': 'Gallery',
        'title-shoots': 'Our <span>Shoots</span>',
        'lead-shoots': 'A look at recent work — real shots from real sessions.',
        'eyebrow-pricing': 'Pricing',
        'title-pricing': 'Simple <span>Packages</span>',
        'pkg-starter': 'Local',
        'pkg-starter-desc': 'For Local Jobs',
        'pkg-pro': 'Globle',
        'pkg-pro-desc': 'For Both International &amp; Local Jobs',
        'pkg-exec': 'Premium',
        'pkg-exec-desc': 'Globle + LinkedIn Services',
        'contact-title': 'Transform Your Career Today.',
        'contact-sub': 'Our consultants are ready. Leave your details and we\'ll be in touch within 4 hours.',
        'form-submit': 'Send Details →'
    },
    'si': {
        'nav-services': 'සේවාවන්',
        'nav-designs': 'අපගේ මෝස්තර',
        'nav-shoots': 'අපගේ ඡායාරූප',
        'nav-packages': 'පැකේජ',
        'nav-contact': 'සම්බන්ධ වන්න',
        'get-started': 'ආරම්භ කරන්න',
        'hero-badge': 'වෘත්තීන් 2,700+ සාර්ථක කර ඇත',
        'hero-title': 'සිහින රැකියාව සඳහා <span class="highlight">ATS-Friendly</span> CV එකක් සාදා ගන්න',
        'hero-sub': 'නවීන තාක්ෂණය සහ දක්ෂ ලේඛන කලාව සමඟින් ඔබේ CV පත්‍රිකාව සම්මුඛ පරීක්ෂණ දක්වා රැගෙන යන්නෙමු.',
        'cta-create': 'මගේ CV එක සාදන්න →',
        'cta-pricing': 'මිල ගණන් බලන්න',
        'stat-cvs': 'CV පත්‍රිකා',
        'stat-ats': 'ATS සාර්ථකත්වය',
        'stat-time': 'ප්‍රතිචාර කාලය',
        'eyebrow-designs': 'සැලසුම්',
        'title-designs': 'වෘත්තීය <span>මෝස්තර</span>',
        'eyebrow-shoots': 'ගැලරිය',
        'title-shoots': 'අපගේ <span>ඡායාරූප</span>',
        'lead-shoots': 'අපගේ මෑත වැඩ කටයුතු කිහිපයක්.',
        'eyebrow-pricing': 'මිල ගණන්',
        'title-pricing': 'සරල <span>පැකේජ</span>',
        'pkg-starter': 'දේශීය',
        'pkg-starter-desc': 'දේශීය රැකියා සඳහා',
        'pkg-pro': 'ග්ලෝබල්',
        'pkg-pro-desc': 'ජාත්‍යන්තර සහ දේශීය රැකියා සඳහා',
        'pkg-exec': 'ප්‍රිමියම්',
        'pkg-exec-desc': 'ග්ලෝබල් + LinkedIn සේවා',
        'contact-title': 'අදම ඔබේ වෘත්තීය ජීවිතය වෙනස් කරන්න.',
        'contact-sub': 'අපගේ උපදෙස් ලබා ගැනීමට ඔබේ තොරතුරු ලබා දෙන්න. පැය 4ක් ඇතුළත අපි ඔබව අමතන්නෙමු.',
        'form-submit': 'තොරතුරු යවන්න →'
    },
    'ta': {
        'nav-services': 'சேவைகள்',
        'nav-designs': 'எங்கள் வடிவமைப்புகள்',
        'nav-shoots': 'எங்கள் புகைப்படங்கள்',
        'nav-packages': 'தொகுப்புகள்',
        'nav-contact': 'தொடர்புக்கு',
        'get-started': 'தொடங்குங்கள்',
        'hero-badge': '2,700+ மாற்றப்பட்ட வாழ்க்கைகள்',
        'hero-title': 'உங்கள் கனவு வேலையை <span class="highlight">ATS-Friendly</span> CV உடன் பெறுங்கள்',
        'hero-sub': 'நிபுணத்துவ கதைசொல்லல் மற்றும் தொழில்நுட்பத்துடன் உங்கள் CV வடிவமைக்கப்படுகிறது.',
        'cta-create': 'எனது CV ஐ உருவாக்கு →',
        'cta-pricing': 'விலைகளைப் பார்க்க',
        'stat-cvs': 'எழுதப்பட்ட CVகள்',
        'stat-ats': 'ATS தேர்ச்சி விகிதம்',
        'stat-time': 'பதில் நேரம்',
        'eyebrow-designs': 'மாதிரிகள்',
        'title-designs': 'தொழில்முறை <span>அமைப்புகள்</span>',
        'eyebrow-shoots': 'கேலரி',
        'title-shoots': 'எங்கள் <span>புகைப்படங்கள்</span>',
        'lead-shoots': 'எங்கள் சமீபத்திய பணிகளின் தோற்றம்.',
        'eyebrow-pricing': 'விலை',
        'title-pricing': 'எளிய <span>தொகுப்புகள்</span>',
        'pkg-starter': 'உள்ளூர்',
        'pkg-starter-desc': 'உள்ளூர் வேலைகளுக்கு',
        'pkg-pro': 'குளோபல்',
        'pkg-pro-desc': 'சர்வதேச மற்றும் உள்ளூர் வேலைகளுக்கு',
        'pkg-exec': 'பிரீமியம்',
        'pkg-exec-desc': 'குளோபல் + LinkedIn சேவைகள்',
        'contact-title': 'இன்றே உங்கள் வாழ்க்கையை மாற்றிக் கொள்ளுங்கள்.',
        'contact-sub': 'எங்கள் ஆலோசகர்கள் தயார். உங்கள் விவரங்களை விடுங்கள், 4 மணி நேரத்திற்குள் உங்களைத் தொடர்புகொள்வோம்.',
        'form-submit': 'விவரங்களை அனுப்பு →'
    }
};

function setLanguage(lang) {
    // Save preference
    localStorage.setItem('preferredLang', lang);
    
    // Update active button state
    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.classList.remove('active');
        if(btn.textContent.toLowerCase().includes(lang === 'en' ? 'en' : lang === 'si' ? 'සිං' : 'த')) {
            btn.classList.add('active');
        }
    });

    // Update text content
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (translations[lang][key]) {
            el.innerHTML = translations[lang][key];
        }
    });
}

// Initialize Language
document.addEventListener('DOMContentLoaded', () => {
    const savedLang = localStorage.getItem('preferredLang') || 'en';
    setLanguage(savedLang);
});


// --- 2. Existing Reveal on scroll ---
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -32px 0px' });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// --- 3. Existing Navbar shadow ---
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 40);
}, { passive: true });

// --- 4. Number counter ---
function runCounter(el) {
  const target = parseInt(el.dataset.target, 10);
  const suffix = el.dataset.suffix || '';
  const duration = 1600;
  let start = null;
  const step = ts => {
    if (!start) start = ts;
    const p = Math.min((ts - start) / duration, 1);
    const ease = 1 - Math.pow(1 - p, 3);
    el.textContent = Math.floor(ease * target).toLocaleString() + suffix;
    if (p < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}

const counterObs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { runCounter(e.target); counterObs.unobserve(e.target); } });
}, { threshold: 0.5 });
document.querySelectorAll('[data-counter]').forEach(el => counterObs.observe(el));

// --- 4b. Our Shoots lightbox ---
(function () {
  const grid = document.getElementById('shootsGrid');
  if (!grid) return;

  const items = Array.from(grid.querySelectorAll('.shoot-item img'));
  const lightbox = document.getElementById('shootsLightbox');
  const lightboxImg = document.getElementById('shootsLightboxImg');
  const btnClose = document.getElementById('shootsClose');
  const btnPrev = document.getElementById('shootsPrev');
  const btnNext = document.getElementById('shootsNext');
  let current = 0;

  function open(i) {
    current = i;
    lightboxImg.src = items[current].src;
    lightboxImg.alt = items[current].alt;
    lightbox.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function close() {
    lightbox.classList.remove('open');
    document.body.style.overflow = '';
  }
  function show(delta) {
    current = (current + delta + items.length) % items.length;
    lightboxImg.src = items[current].src;
    lightboxImg.alt = items[current].alt;
  }

  items.forEach((img, i) => img.closest('.shoot-item').addEventListener('click', () => open(i)));
  btnClose.addEventListener('click', close);
  btnPrev.addEventListener('click', () => show(-1));
  btnNext.addEventListener('click', () => show(1));
  lightbox.addEventListener('click', (e) => { if (e.target === lightbox) close(); });
  document.addEventListener('keydown', (e) => {
    if (!lightbox.classList.contains('open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') show(-1);
    if (e.key === 'ArrowRight') show(1);
  });

  // Show more / show less
  const moreBtn = document.getElementById('shootsMoreBtn');
  const moreLabel = document.getElementById('shootsMoreLabel');
  const hiddenItems = Array.from(grid.querySelectorAll('.shoot-item.shoot-hidden'));
  let expanded = false;

  if (moreBtn && hiddenItems.length) {
    moreBtn.addEventListener('click', () => {
      expanded = !expanded;
      moreBtn.classList.toggle('open', expanded);
      moreLabel.textContent = expanded ? 'Show Less' : 'Show More';

      if (expanded) {
        hiddenItems.forEach((item, i) => {
          item.classList.remove('shoot-hidden');
          item.classList.add('shoot-revealing');
          item.style.animationDelay = (i * 0.06) + 's';
        });
      } else {
        hiddenItems.forEach(item => {
          item.classList.add('shoot-hidden');
          item.classList.remove('shoot-revealing');
        });
        document.getElementById('shoots').scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  }
})();

// --- 5. Form handling ---
const form = document.getElementById('apexForm');
if (form) {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = this.querySelector('.form-submit');
    btn.textContent = '...';
    setTimeout(() => {
      btn.textContent = '✓ Success!';
      btn.style.background = '#059669';
      this.reset();
    }, 1500);
  });
}