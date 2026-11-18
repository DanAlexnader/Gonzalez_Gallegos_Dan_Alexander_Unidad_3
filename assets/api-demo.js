const root=document.getElementById('api-demo');
document.getElementById('ejecutar').addEventListener('click',async()=>{
 const m=document.getElementById('metodo').value,id=document.getElementById('producto-id').value,out=document.getElementById('resultado'),btn=document.getElementById('ejecutar');
 if(m==='DELETE'&&!confirm('¿Desactivar este producto?'))return;
 btn.disabled=true;
 try{const url=root.dataset.url+(id?'?id='+encodeURIComponent(id):'');const options={method:m,headers:{'X-CSRF-Token':root.dataset.csrf}};if(['POST','PUT'].includes(m)){options.headers['Content-Type']='application/json';options.body=document.getElementById('json').value;}const r=await fetch(url,options);out.textContent=m+' '+url+'\nHTTP '+r.status+'\n'+JSON.stringify(await r.json(),null,2);}catch(e){out.textContent='Error de solicitud: '+e.message;}finally{btn.disabled=false;}
});
