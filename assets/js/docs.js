document.addEventListener('DOMContentLoaded', () => {
    if (typeof hljs !== 'undefined') {
        hljs.highlightAll();
    }

    const searchInput = document.getElementById('searchDocs');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(searchDocs, 300));
    }

    const faqItems = document.querySelectorAll('.faq-item h3');
    faqItems.forEach(item => {
        item.addEventListener('click', () => {
            const content = item.nextElementSibling;
            if (content && content.tagName === 'P') {
                content.style.display = content.style.display === 'none' ? 'block' : 'none';
            }
        });
    });

    const sidebarLinks = document.querySelectorAll('.docs-nav a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            sidebarLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            
            const targetId = link.getAttribute('href');
            if (targetId && targetId.startsWith('#')) {
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                if (id) {
                    sidebarLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${id}`) {
                            link.classList.add('active');
                        }
                    });
                }
            }
        });
    }, { threshold: 0.5, rootMargin: '-80px 0px -80px 0px' });

    document.querySelectorAll('.doc-section').forEach(section => {
        observer.observe(section);
    });
});

function searchDocs() {
    const searchTerm = document.getElementById('searchDocs').value.toLowerCase();
    const sections = document.querySelectorAll('.doc-section');
    let firstVisible = null;

    sections.forEach(section => {
        const text = section.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            section.style.display = 'block';
            if (!firstVisible) firstVisible = section;
        } else {
            section.style.display = 'none';
        }
    });

    if (firstVisible) {
        firstVisible.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    const navLinks = document.querySelectorAll('.docs-nav a');
    navLinks.forEach(link => {
        const targetId = link.getAttribute('href');
        if (targetId && targetId.startsWith('#')) {
            const target = document.querySelector(targetId);
            if (target) {
                link.style.display = target.style.display === 'none' ? 'none' : 'block';
            }
        }
    });

    showToast(`Found ${Array.from(sections).filter(s => s.style.display !== 'none').length} results`, 'info');
}
