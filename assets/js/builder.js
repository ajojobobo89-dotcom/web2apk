// LIFX Web2APK - Builder Page JavaScript
// Created by @lifxcodetZ

// ========== GLOBAL VARIABLES ==========

let currentAPKData = null;
let buildHistory = storage.get('buildHistory', []);
let selectedMethod = 'web2apk';
let buildTimer = null;

// ========== INITIALIZATION ==========

document.addEventListener('DOMContentLoaded', () => {
    loadBuildHistory();
    checkAPIsStatus();
    setupEventListeners();
});

// ========== TAB SWITCHING ==========

function switchInputTab(tabName) {
    // Update tabs
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.input-panel').forEach(panel => panel.classList.remove('active'));
    
    // Activate selected tab
    if (tabName === 'html') {
        document.querySelectorAll('.tab-btn')[0].classList.add('active');
        document.getElementById('html-input').classList.add('active');
        document.getElementById('inputType').textContent = 'HTML Code';
    } else if (tabName === 'url') {
        document.querySelectorAll('.tab-btn')[1].classList.add('active');
        document.getElementById('url-input').classList.add('active');
        document.getElementById('inputType').textContent = 'Website URL';
    } else if (tabName === 'zip') {
        document.querySelectorAll('.tab-btn')[2].classList.add('active');
        document.getElementById('zip-input').classList.add('active');
        document.getElementById('inputType').textContent = 'ZIP File';
    }
}

// ========== FILE HANDLING ==========

function updateFileInfo(input) {
    const fileInfo = document.getElementById('fileInfo');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        fileInfo.innerHTML = `
            <i class="fas fa-check-circle" style="color: var(--success);"></i>
            ${file.name} (${formatFileSize(file.size)})
        `;
    } else {
        fileInfo.innerHTML = 'No file selected';
    }
}

// ========== PREVIEW FUNCTIONS ==========

function previewHTML() {
    const html = document.getElementById('htmlCode').value;
    const iframe = document.querySelector('#previewFrame iframe');
    
    if (!html.trim()) {
        showToast('Please enter HTML code', 'warning');
        return;
    }
    
    iframe.srcdoc = html;
    showToast('Preview updated', 'success');
}

function refreshPreview() {
    const activeTab = document.querySelector('.tab-btn.active');
    if (activeTab.textContent.includes('HTML')) {
        previewHTML();
    } else if (activeTab.textContent.includes('URL')) {
        fetchURL();
    }
}

function fetchURL() {
    const url = document.getElementById('websiteUrl').value;
    if (!url) {
        showToast('Please enter URL', 'warning');
        return;
    }
    
    showToast('Fetching website...', 'info');
    
    // Simulate fetch (in production, use proxy server)
    setTimeout(() => {
        const iframe = document.querySelector('#previewFrame iframe');
        iframe.src = url;
        showToast('Website loaded', 'success');
    }, 1500);
}

// ========== APK GENERATION ==========

function validateForm() {
    const appName = document.getElementById('appName').value;
    const packageName = document.getElementById('packageName').value;
    const version = document.getElementById('version').value;
    const versionCode = document.getElementById('versionCode').value;
    
    if (!appName) {
        showToast('App name is required', 'error');
        return false;
    }
    
    if (!packageName) {
        showToast('Package name is required', 'error');
        return false;
    }
    
    if (!/^[a-z][a-z0-9_]*(\.[a-z][a-z0-9_]*)+$/.test(packageName)) {
        showToast('Invalid package name format', 'error');
        return false;
    }
    
    if (!version || !versionCode) {
        showToast('Version and version code are required', 'error');
        return false;
    }
    
    // Check active input
    const activeTab = document.querySelector('.tab-btn.active');
    if (activeTab.textContent.includes('HTML')) {
        const html = document.getElementById('htmlCode').value;
        if (!html.trim()) {
            showToast('HTML code is required', 'error');
            return false;
        }
    } else if (activeTab.textContent.includes('URL')) {
        const url = document.getElementById('websiteUrl').value;
        if (!url) {
            showToast('URL is required', 'error');
            return false;
        }
    } else if (activeTab.textContent.includes('ZIP')) {
        const file = document.getElementById('zipFile').files[0];
        if (!file) {
            showToast('ZIP file is required', 'error');
            return false;
        }
    }
    
    return true;
}

function generateAPK() {
    if (!validateForm()) return;
    
    // Show progress
    document.getElementById('progressContainer').style.display = 'block';
    document.getElementById('generateBtn').disabled = true;
    
    let progress = 0;
    buildTimer = setInterval(() => {
        progress += Math.random() * 5;
        if (progress >= 100) {
            progress = 100;
            clearInterval(buildTimer);
            completeBuild();
        }
        
        updateProgress(progress);
    }, 300);
    
    // Simulate build steps
    setTimeout(() => updateBuildStep('Compiling resources...'), 1000);
    setTimeout(() => updateBuildStep('Generating AndroidManifest.xml...'), 2000);
    setTimeout(() => updateBuildStep('Building APK...'), 3000);
    setTimeout(() => updateBuildStep('Signing APK...'), 4000);
    setTimeout(() => updateBuildStep('Optimizing...'), 5000);
}

function updateProgress(percentage) {
    document.getElementById('progressFill').style.width = percentage + '%';
    document.getElementById('progressPercentage').textContent = Math.round(percentage) + '%';
}

function updateBuildStep(message) {
    document.getElementById('progressText').textContent = message;
}

function completeBuild() {
    // Hide progress
    document.getElementById('progressContainer').style.display = 'none';
    document.getElementById('generateBtn').disabled = false;
    
    // Show result
    document.getElementById('resultContainer').style.display = 'flex';
    
    // Generate random file size (1-10 MB)
    const fileSize = Math.floor(Math.random() * 9) + 1;
    
    currentAPKData = {
        id: Date.now(),
        name: document.getElementById('appName').value,
        package: document.getElementById('packageName').value,
        version: document.getElementById('version').value,
        size: fileSize,
        timestamp: new Date().toISOString()
    };
    
    // Add to history
    buildHistory.unshift(currentAPKData);
    storage.set('buildHistory', buildHistory);
    
    // Update result message
    document.getElementById('resultTitle').textContent = 'APK Generated Successfully!';
    document.getElementById('resultMessage').textContent = 
        `${currentAPKData.name} v${currentAPKData.version} • ${fileSize} MB`;
    
    showToast('APK generated successfully!', 'success');
}

function downloadAPK() {
    if (!currentAPKData) {
        showToast('No APK to download', 'warning');
        return;
    }
    
    // In production, this would download the real APK
    // For demo, create a dummy file
    const content = 'This is a real APK file';
    const blob = new Blob([content], { type: 'application/vnd.android.package-archive' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${currentAPKData.name.replace(/\s+/g, '_')}.apk`;
    a.click();
    
    window.URL.revokeObjectURL(url);
    
    showToast('Download started', 'success');
}

function viewDetails() {
    if (!currentAPKData) return;
    
    alert(`
APK Details:
------------
Name: ${currentAPKData.name}
Package: ${currentAPKData.package}
Version: ${currentAPKData.version}
Size: ${currentAPKData.size} MB
Generated: ${formatDate(currentAPKData.timestamp)}
    `);
}

function resetForm() {
    if (buildTimer) {
        clearInterval(buildTimer);
    }
    
    document.getElementById('progressContainer').style.display = 'none';
    document.getElementById('generateBtn').disabled = false;
    document.getElementById('progressFill').style.width = '0%';
    document.getElementById('resultContainer').style.display = 'none';
    
    // Reset form fields
    document.getElementById('appName').value = 'LIFX App';
    document.getElementById('packageName').value = 'com.lifx.myapp';
    document.getElementById('version').value = '1.0.0';
    document.getElementById('versionCode').value = '1';
    document.getElementById('htmlCode').value = '';
    document.getElementById('websiteUrl').value = '';
    document.getElementById('zipFile').value = '';
    document.getElementById('fileInfo').innerHTML = 'No file selected';
    
    // Reset preview
    const iframe = document.querySelector('#previewFrame iframe');
    iframe.srcdoc = '<html><body style="background:#f5f5f5;display:flex;align-items:center;justify-content:center;height:100%;color:#666;"><h2>Enter HTML to preview</h2></body></html>';
    
    showToast('Form reset', 'info');
}

// ========== API STATUS ==========

async function checkAPIsStatus() {
    const status = await checkAPIStatus();
    
    // Update status dots
    const dots = document.querySelectorAll('.status-dot');
    if (status.web2apk === 'online') {
        dots[0].className = 'status-dot online';
    }
    if (status.appyet === 'online') {
        dots[1].className = 'status-dot online';
    }
    if (status.mit === 'online') {
        dots[2].className = 'status-dot online';
    }
}

// ========== BUILD HISTORY ==========

function loadBuildHistory() {
    const tbody = document.getElementById('historyTableBody');
    if (!tbody) return;
    
    if (buildHistory.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center">No build history yet</td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    buildHistory.slice(0, 10).forEach((item, index) => {
        html += `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>${item.name}</strong><br>
                    <small class="text-muted">v${item.version}</small>
                </td>
                <td>${item.package}</td>
                <td>${formatDate(item.timestamp, 'relative')}</td>
                <td>${item.size} MB</td>
                <td><span class="badge-success">Success</span></td>
                <td>
                    <button class="btn-icon" onclick="downloadHistory('${item.id}')">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn-icon" onclick="viewHistory('${item.id}')">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

function downloadHistory(id) {
    const item = buildHistory.find(i => i.id == id);
    if (item) {
        currentAPKData = item;
        downloadAPK();
    }
}

function viewHistory(id) {
    const item = buildHistory.find(i => i.id == id);
    if (item) {
        viewDetails.call({ currentAPKData: item });
    }
}

// ========== EVENT LISTENERS ==========

function setupEventListeners() {
    // Icon upload
    const iconFile = document.getElementById('iconFile');
    if (iconFile) {
        iconFile.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('iconPreview').innerHTML = 
                        `<img src="${ev.target.result}" style="width: 64px; height: 64px; border-radius: 0.75rem;">`;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl+Enter to generate
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            generateAPK();
        }
        
        // Ctrl+P to preview
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            previewHTML();
        }
    });
}
