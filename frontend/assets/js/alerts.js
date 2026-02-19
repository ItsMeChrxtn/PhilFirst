// Reusable SweetAlert helper
// Requires SweetAlert2 to be loaded first (Swal)
function notify(type, message, title = '') {
  // type: success | error | info | warning
  const config = {
    title: title || (type === 'success' ? 'Success' : (type === 'error' ? 'Error' : 'Notice')),
    text: message,
    icon: type,
    confirmButtonColor: '#16a34a'
  };
  return Swal.fire(config);
}

// Convenience wrappers
function notifySuccess(message, title = '') { return notify('success', message, title); }
function notifyError(message, title = '') { return notify('error', message, title); }
