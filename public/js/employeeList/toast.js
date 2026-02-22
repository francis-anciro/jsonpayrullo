// Handles auto-hide for any error toast
setTimeout(() => {
    const toast = document.getElementById('toast-error');
    if (toast) {
        toast.classList.add('opacity-0', '-translate-y-4', 'transition-all', 'duration-500');
        setTimeout(() => toast.remove(), 500);
    }
}, 5000);