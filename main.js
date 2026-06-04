/* ApexCV — main.js */

// --- 1. Multi-Language System ---
const translations = {
    'en': {
        'nav-services': 'Services',
        'nav-designs': 'Our Designs',
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
        'eyebrow-pricing': 'Pricing',
        'title-pricing': 'Simple <span>Packages</span>',
        'pkg-starter': 'Starter',
        'pkg-pro': 'Professional',
        'pkg-exec': 'Executive',
        'contact-title': 'Transform Your Career Today.',
        'contact-sub': 'Our consultants are ready. Leave your details and we\'ll be in touch within 4 hours.',
        'form-submit': 'Send Details →'
    },
    'si': {
        'nav-services': 'සේවාවන්',
        'nav-designs': 'අපගේ මෝස්තර',
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
        'eyebrow-pricing': 'මිල ගණන්',
        'title-pricing': 'සරල <span>පැකේජ</span>',
        'pkg-starter': 'ආරම්භක',
        'pkg-pro': 'වෘත්තීය',
        'pkg-exec': 'විධායක',
        'contact-title': 'අදම ඔබේ වෘත්තීය ජීවිතය වෙනස් කරන්න.',
        'contact-sub': 'අපගේ උපදෙස් ලබා ගැනීමට ඔබේ තොරතුරු ලබා දෙන්න. පැය 4ක් ඇතුළත අපි ඔබව අමතන්නෙමු.',
        'form-submit': 'තොරතුරු යවන්න →'
    },
    'ta': {
        'nav-services': 'சேவைகள்',
        'nav-designs': 'எங்கள் வடிவமைப்புகள்',
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
        'eyebrow-pricing': 'விலை',
        'title-pricing': 'எளிய <span>தொகுப்புகள்</span>',
        'pkg-starter': 'ஆரம்பநிலை',
        'pkg-pro': 'தொழில்முறை',
        'pkg-exec': 'நிர்வாகி',
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