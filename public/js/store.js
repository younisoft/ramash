document.querySelectorAll('[data-open]').forEach(button => {
 button.addEventListener('click',()=>{const dialog=document.getElementById(button.dataset.open);if(dialog){dialog.showModal();dialog.querySelector('input')?.focus();}});
});
document.querySelectorAll('dialog').forEach(dialog=>dialog.addEventListener('click',event=>{if(event.target===dialog){const rect=dialog.getBoundingClientRect();if(event.clientX<rect.left||event.clientX>rect.right||event.clientY<rect.top||event.clientY>rect.bottom)dialog.close();}}));
document.querySelectorAll('[data-dismiss]').forEach(button=>button.addEventListener('click',()=>button.closest('.toast').remove()));
document.querySelectorAll('[data-quantity]').forEach(button=>button.addEventListener('click',()=>{const input=button.closest('.quantity').querySelector('input');input.value=Math.max(Number(input.min)||1,Math.min(Number(input.max)||10,(Number(input.value)||1)+Number(button.dataset.quantity)));input.dispatchEvent(new Event('change',{bubbles:true}));}));
document.querySelectorAll('form').forEach(form=>form.addEventListener('submit',()=>{const button=form.querySelector('[data-submit]');if(button){button.disabled=true;button.setAttribute('aria-busy','true');}}));
window.addEventListener('pageshow',()=>document.querySelectorAll('[data-submit]').forEach(button=>{button.disabled=false;button.removeAttribute('aria-busy');}));
