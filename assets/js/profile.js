document.getElementById('avatarContainer')?.addEventListener('click', () => {
    document.getElementById('avatarUpload').click();
});

document.getElementById('avatarUpload')?.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('avatarPreview').src = ev.target.result;
            storage.set('avatar', ev.target.result);
            showToast('Avatar updated', 'success');
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

document.getElementById('profileForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const profileData = {
        firstName: document.getElementById('firstName').value,
        lastName: document.getElementById('lastName').value,
        username: document.getElementById('username').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        bio: document.getElementById('bio').value
    };
    
    storage.set('profile', profileData);
    document.getElementById('displayName').textContent = `${profileData.firstName} ${profileData.lastName}`;
    showToast('Profile updated successfully', 'success');
});

function generateNewKey() {
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    const key = 'lifx_' + Array.from({length: 24}, () => chars[Math.floor(Math.random() * chars.length)]).join('');
    
    const keysList = document.querySelector('.api-keys-list');
    const newKeyHtml = `
        <div class="api-key-item">
            <div class="key-info">
                <h4>New API Key</h4>
                <code>${key}</code>
                <span class="badge-success">Active</span>
            </div>
            <div class="key-actions">
                <span class="key-usage">Generated just now</span>
                <button class="btn-icon" onclick="copyKey('${key}')">
                    <i class="fas fa-copy"></i>
                </button>
                <button class="btn-icon" onclick="revokeKey('new')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    keysList.insertAdjacentHTML('afterbegin', newKeyHtml);
    showToast('New API key generated', 'success');
}

function copyKey(key) {
    copyToClipboard(key);
}

function revokeKey(keyId) {
    if (confirm('Are you sure you want to revoke this API key?')) {
        const keyItem = event.target.closest('.api-key-item');
        if (keyItem) {
            keyItem.remove();
            showToast('API key revoked', 'success');
        }
    }
}

function exportData() {
    const data = {
        profile: storage.get('profile', {}),
        history: storage.get('buildHistory', []),
        preferences: {
            defaultMethod: document.getElementById('defaultMethod')?.value,
            emailNotif: document.getElementById('emailNotif')?.checked,
            telegramNotif: document.getElementById('telegramNotif')?.checked,
            autoCleanup: document.getElementById('autoCleanup')?.checked
        }
    };
    
    const dataStr = JSON.stringify(data, null, 2);
    const blob = new Blob([dataStr], { type: 'application/json' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `lifx-user-data-${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    window.URL.revokeObjectURL(url);
    showToast('Data exported successfully', 'success');
}

function deleteAccount() {
    if (confirm('WARNING: This will permanently delete your account and all data. This action cannot be undone. Continue?')) {
        storage.clear();
        showToast('Account deleted', 'success');
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 2000);
    }
}

document.getElementById('emailNotif')?.addEventListener('change', function(e) {
    storage.set('emailNotif', e.target.checked);
});

document.getElementById('telegramNotif')?.addEventListener('change', function(e) {
    storage.set('telegramNotif', e.target.checked);
});

document.getElementById('autoCleanup')?.addEventListener('change', function(e) {
    storage.set('autoCleanup', e.target.checked);
});

document.getElementById('defaultMethod')?.addEventListener('change', function(e) {
    storage.set('defaultMethod', e.target.value);
    showToast('Preference saved', 'success');
});

const savedProfile = storage.get('profile', {});
if (savedProfile.firstName) {
    document.getElementById('firstName').value = savedProfile.firstName || '';
    document.getElementById('lastName').value = savedProfile.lastName || '';
    document.getElementById('username').value = savedProfile.username || '';
    document.getElementById('email').value = savedProfile.email || '';
    document.getElementById('phone').value = savedProfile.phone || '';
    document.getElementById('bio').value = savedProfile.bio || '';
    document.getElementById('displayName').textContent = `${savedProfile.firstName || 'John'} ${savedProfile.lastName || 'Doe'}`;
}

const savedAvatar = storage.get('avatar');
if (savedAvatar) {
    document.getElementById('avatarPreview').src = savedAvatar;
}

document.getElementById('emailNotif').checked = storage.get('emailNotif', true);
document.getElementById('telegramNotif').checked = storage.get('telegramNotif', false);
document.getElementById('autoCleanup').checked = storage.get('autoCleanup', true);
document.getElementById('defaultMethod').value = storage.get('defaultMethod', 'web2apk');
