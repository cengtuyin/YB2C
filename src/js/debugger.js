const SecurityGuard = {
    init() {
        this.antiDebug();
        this.consoleProtection();
        this.memoryGuard();
        // this.navigationLock();
    },
    
    antiDebug() {
        const detector = () => {
            const start = Date.now();
            debugger;
            if (Date.now() - start > 100) {
                this.emergencyShutdown();
            }
        };
        
        setInterval(detector, 500);
        detector();
    },
    
    consoleProtection() {
        ['log', 'info', 'warn', 'error', 'debug'].forEach(method => {
            const original = console[method];
            console[method] = function(...args) {
                SecurityGuard.logUsage(method);
                original.apply(console, args);
            };
        });
    },
    
    memoryGuard() {
        setInterval(() => {
            if (performance.memory) {
                const used = performance.memory.usedJSHeapSize;
                const limit = performance.memory.jsHeapSizeLimit;
                if (used / limit > 0.8) {
                    this.emergencyShutdown();
                }
            }
        }, 5000);
    },
    
    navigationLock() {
        window.addEventListener('beforeunload', (e) => {
            e.preventDefault();
            e.returnValue = '系统正在处理安全检测，请勿离开页面';
        });
    },
    
    logUsage(method) {
        const logs = JSON.parse(localStorage.getItem('console_logs') || '[]');
        logs.push({ method, timestamp: Date.now(), url: window.location.href });
        localStorage.setItem('console_logs', JSON.stringify(logs));
        
        if (logs.length > 10) {
            this.emergencyShutdown();
        }
    },
    
    emergencyShutdown() {
        document.body.innerHTML = `
            <div style="position:fixed;top:0;left:0;width:100%;height:100%;background:black;color:red;display:flex;align-items:center;justify-content:center;z-index:9999;">
                <h1>安全警告：检测到调试行为</h1>
            </div>
        `;
        window.stop();
        document.addEventListener('click', (e) => e.preventDefault());
        document.addEventListener('keydown', (e) => e.preventDefault());
        localStorage.clear();
        sessionStorage.clear();
        setTimeout(() => {
            window.location.replace('about:blank');
        }, 1000);
    }
};
SecurityGuard.init();