(async()=>{
 const nodes=document.querySelectorAll('[data-mxn]'); if(!nodes.length)return;
 const status=document.getElementById('moneda-estado');
 try { const res=await fetch(status.dataset.url); const d=await res.json(); if(!res.ok||!d.ok)throw Error();
 for(const n of nodes)n.textContent='≈ '+new Intl.NumberFormat('en-US',{style:'currency',currency:'USD'}).format(Number(n.dataset.mxn)*d.tasa)+' USD';
 status.textContent='Conversión informativa MXN → USD · Frankfurter · '+d.fecha+' · 1 MXN = '+d.tasa+' USD';
 }catch{status.textContent='Conversión USD no disponible. Los precios en MXN siguen vigentes.';}
})();
