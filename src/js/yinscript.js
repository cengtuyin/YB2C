
window.onscroll = function() {
    const navbar = document.querySelector(".Header");
    const sticky = navbar.offsetTop;
    if (window.pageYOffset > 0) {
        navbar.classList.add("Header_Sub");
    } else {
        navbar.classList.remove("Header_Sub");
    }
};

var papetitle = document.title;

class HeaderManager {
    constructor() {
        this.currentSection = 'home';
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.setInitialState();
    }
    
    bindEvents() {
        document.querySelectorAll('.Header_List a').forEach(link => {
            link.addEventListener('click', (e) => this.handleNavClick(e));
        });
        window.addEventListener('scroll', () => this.handleScroll());
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
    }
    
    handleNavClick(e) {
        e.preventDefault();
        const link = e.currentTarget;
        const section = link.getAttribute('data-section');
        
        this.setActiveSection(section);
        this.navigateToSection(section);
    }
    
    setActiveSection(section) {
        document.querySelectorAll('.Header_List a').forEach(link => {
            link.classList.remove('active');
        });
        const activeLink = document.querySelector(`[data-section="${section}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
            this.currentSection = section;
        }
    }
    
    navigateToSection(section) {
        window.location.hash = section;
        this.loadSectionContent(section);
        this.dispatchSectionChange(section);
    }
    
    loadSectionContent(section) {
        this.showLoadingState();
        // setTimeout(() => {
            this.hideLoadingState();
            this.updatePageTitle(section);
        // }, 300);
    }
    
    setInitialState() {
        const hash = window.location.hash.substring(1);
        const initialSection = hash || 'home';
        this.setActiveSection(initialSection);
    }
    
    handleScroll() {
        const header = document.querySelector('.Header');
        if (window.scrollY > 10) {
            header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
        } else {
            header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        }
    }
    
    handleKeyboard(e) {
        if (e.altKey) {
            const links = Array.from(document.querySelectorAll('.Header_List a'));
            const currentIndex = links.findIndex(link => link.classList.contains('active'));
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    const prevIndex = (currentIndex - 1 + links.length) % links.length;
                    links[prevIndex].click();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    const nextIndex = (currentIndex + 1) % links.length;
                    links[nextIndex].click();
                    break;
                case 'Home':
                    e.preventDefault();
                    links[0].click();
                    break;
                case 'End':
                    e.preventDefault();
                    links[links.length - 1].click();
                    break;
            }
        }
    }
    
    showLoadingState() {
        const activeLink = document.querySelector('.Header_List a.active');
        if (activeLink) {
            activeLink.style.opacity = '0.7';
        }
    }
    
    hideLoadingState() {
        const activeLink = document.querySelector('.Header_List a.active');
        if (activeLink) {
            activeLink.style.opacity = '1';
        }
    }
    
    updatePageTitle(section) {
        const sectionTitles = {
            'home': '首页',
            'data': '数据',
            'advice': '反馈',
            'settings': '配置',
            'status': '公告',
            'account': '用户',
            'api': '接口'
        };
        
        const title = sectionTitles[section] || '未知';
        document.title = `${papetitle} - ${title}`;
    }
    
    dispatchSectionChange(section) {
        // 触发自定义事件，其他组件可以监听
        const event = new CustomEvent('sectionChange', {
            detail: { section }
        });
        document.dispatchEvent(event);
    }
}

// 初始化 Header
document.addEventListener('DOMContentLoaded', () => {
    new HeaderManager();
});