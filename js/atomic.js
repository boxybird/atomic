document.body.addEventListener('htmx:configRequest', (event) => {
  event.detail.headers['Atomic-Data'] = JSON.stringify(window.atomicData) // atomicData uses wp_localize_script()
})