function z(e,r,n=null){e.preventDefault(),n===null&&(n=r.classList.contains("open")?0:1),r.classList.toggle("open",n>0),r.classList.toggle("close",n<=0)}const D=e=>{if(!e)throw new Error("Canvas is required to initialize defaults.");return{text:{fontSize:30,fill:"#000000",fontFamily:"Arial",hasControls:!0,editable:!0,stay:!0},მაისური:{box:{strokeDashArray:[5,5],fill:"transparent",selectable:!1,evented:!1,stay:!0,stay_when_pos:!0,left:e.width/2,top:e.height/2-20,width:e.width*.4,height:e.height*.4,originX:"center",originY:"center"}},პოლო:{box:{strokeDashArray:[5,5],fill:"transparent",selectable:!1,evented:!1,stay:!0,stay_when_pos:!0,left:e.width/2,top:e.height/2+7,width:e.width*.3,height:e.height*.23,originX:"center",originY:"center"}},ჰუდი:{box:{strokeDashArray:[5,5],fill:"transparent",selectable:!1,evented:!1,stay:!0,stay_when_pos:!0,left:e.width/2-10,top:e.height/2-39,width:e.width*.4,height:e.height*.2,originX:"center",originY:"center"}},ქეისი:{box:{strokeDashArray:[5,5],fill:"transparent",selectable:!1,evented:!1,stay:!0,stay_when_pos:!0,left:e.width/2,top:e.height/2+50,width:e.width*.3,height:e.height*.3,originX:"center",originY:"center"}},კეპი:{box:{strokeDashArray:[5,5],fill:"transparent",selectable:!1,evented:!1,stay:!0,stay_when_pos:!0,left:e.width/2,top:e.height/2,width:e.width*.4,height:e.height*.2,originX:"center",originY:"center"}}}},t=new fabric.Canvas("tshirtCanvas"),k=document.querySelector("#product-image"),B=document.querySelector("#design-area"),F=(k==null?void 0:k.getAttribute("data-type"))||"default",Y=k==null?void 0:k.getAttribute("data-id"),A=`${Y}.front_design`,E=`${Y}.back_design`,q=Math.random().toString(36).substring(7);let u={},$=[...document.querySelectorAll(".text-style-btn")],s={current_image_url:"",current_image_side:"front"};const h={font_family:document.querySelector("#font_family"),font_size:document.querySelector("#font_size"),text_color:document.querySelector("#text_color"),btns:$,text_container:document.querySelector("#textInputsContainer"),add_text_btn:document.querySelector("#addTextInput")};let b="",p="",m=null,T,C,x=1;const P=.1;fabric.Object.prototype.controls.deleteControl=new fabric.Control({x:.5,y:-.5,offsetY:0,offsetX:0,cursorStyle:"pointer",mouseUpHandler:function(e,r){const n=r.target,o=n.canvas,l=n?document.getElementById(n.input_id):null,c=l?l.closest(".text-input-group").querySelector(".remove-text-btn"):null;if(c&&l&&l.id==n.input_id&&n.type==="textbox"){l.remove(),c.remove(),o.remove(n),o.requestRenderAll(),f(),g(s.current_image_url);return}o.remove(n),o.requestRenderAll(),f(),g(s.current_image_url)},render:function(e,r,n,o,l){const c=this.sizeX||24;e.save(),e.fillStyle="red",e.beginPath(),e.arc(r,n,c/2,0,Math.PI*2,!1),e.fill(),e.strokeStyle="white",e.lineWidth=2,e.beginPath(),e.moveTo(r-5,n-5),e.lineTo(r+5,n+5),e.moveTo(r+5,n-5),e.lineTo(r-5,n+5),e.stroke(),e.restore()},sizeX:20,sizeY:20});function _e(){ue(),W(),U(),J()}function W(){X(),window.addEventListener("resize",X()),se(),N()}function J(){document.getElementById("zoom-in").addEventListener("click",()=>{x<2&&(x+=P,e(x))}),document.getElementById("zoom-out").addEventListener("click",()=>{x>.5&&(x-=P,e(x))});function e(o){const l=t.getCenter();t.zoomToPoint(new fabric.Point(l.left,l.top),o),r()}function r(){document.getElementById("zoom-level").textContent=Math.round(x*100)+"%"}window.addEventListener("beforeunload",n);function n(){Object.keys(localStorage).forEach(o=>{(o.includes("/colors/")||o===A||o===E||o===s.current_image_side||o===q+".front_design"||o===q+".back_design"||o===q+".front_image"||o===q+".back_image")&&localStorage.removeItem(o)})}te(),pe(),V(),K(),j(),t.on("object:modified",function(o){f(),g(s.current_image_url)}),t.on("selection:created",function(o){const c={...D(t)[F].box};t.getObjects().forEach(i=>{i&&i.type=="rect"&&(i.set({...c,stroke:"#ccc",strokeWidth:2}),t.requestRenderAll())})}),t.on("selection:cleared",function(){t.getObjects().forEach(o=>{o&&o.type=="rect"&&o.set({strokeWidth:0,stroke:""})})})}function N(){const r={...D(t)[F].box};let n=new fabric.Rect({...r,strokeWidth:0,hasControls:!1,lockMovementX:!0,lockMovementY:!0,lockScalingX:!0,lockScalingY:!0,lockRotation:!0});t.add(n);let o=new fabric.Rect({left:r.left,top:r.top,width:r.width,height:r.height,originX:"center",originY:"center",absolutePositioned:!0,stay:r.stay,stay_when_pos:!0,hasControls:!1,lockMovementX:!0,lockMovementY:!0,lockScalingX:!0,lockScalingY:!0,lockRotation:!0});T=new fabric.Group([],{left:0,top:0,clipPath:o,selectable:!1,evented:!0,subTargetCheck:!0,interactive:!0,stay:r.stay,stay_when_pos:!0,hasControls:!1,lockMovementX:!0,lockMovementY:!0,lockScalingX:!0,lockScalingY:!0,lockRotation:!0}),t.add(T),t.designGroup=T,C=t.add.bind(t),t.add=function(...l){return l.forEach(c=>{c!==n&&!c.excludeFromClipping&&(c.clipPath=o),C(c)}),t.requestRenderAll(),f(),g(s.current_image_url),t}}function U(){H(u),ae(h.font_family),le(h.text_color),ie(h.font_size),re(h.btns),G()}function G(){if(!h.add_text_btn||!h.text_container){console.error("Add text button or text container not found");return}h.add_text_btn.addEventListener("click",function(){const e="text_"+Date.now(),r=`
            <div class="text-input-group d-flex align-items-center gap-2" data-input-id="${e}">
                <div class="input-wrapper flex-grow-1">
                    <input type="text" id="${e}" class="form-control input-styled my-4 dynamic-text-input" placeholder="შეიყვანე ტექსტი">
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-text-btn">✕</button>
            </div>

        `;h.text_container.insertAdjacentHTML("beforeend",r);const n=document.getElementById(e);n&&(M([n]),n.closest(".text-input-group").querySelector(".remove-text-btn").addEventListener("click",function(){u[e]&&(t.remove(u[e]),delete u[e],t.requestRenderAll(),f(),g(s.current_image_url)),n.closest(".text-input-group").remove()}))})}function Z(){Object.keys(u).forEach(e=>{const r=document.getElementById(e);r&&(r.value=u[e].text)})}function K(){t.on("mouse:down",function(e){e.target&&(e.target.clipPath&&(t.setActiveObject(e.target),e.target.type==="textbox"&&(m=e.target)),f(),g(s.current_image_url))})}function j(){t.on("resize",function(){const e={left:t.width/2,top:t.height/2,width:t.width*.4,height:t.height*.2};clipPath.set(e),boundingBox.set(e),t.getObjects().forEach(r=>{r!==boundingBox&&!r.excludeFromClipping&&(r.clipPath=clipPath)}),t.requestRenderAll(),f(),g(s.current_image_url)})}function V(){document.querySelectorAll(".color-option").forEach(o=>{o.addEventListener("click",function(l){document.querySelectorAll(".color-option").forEach(c=>c.classList.remove("selected")),o.classList.add("selected"),b=this.getAttribute("data-front-image"),p=this.getAttribute("data-back-image"),b.includes("color")||(b=null),p.includes("color")||(p=null),R(),b&&S(b,"color",p)})});let r=document.querySelector("#showFront");r&&r.addEventListener("click",function(o){o.preventDefault(),console.log("clicked front"),b&&S(b,"pos")});let n=document.querySelector("#showBack");n&&n.addEventListener("click",function(o){o.preventDefault(),console.log("selectedBackImage: ",p),console.log("clicked back"),p&&S(p,"pos")})}function R(){document.querySelectorAll("#customizationForm input, #customizationForm select, #customizationForm button").forEach(r=>{r.disabled=!1})}function Q(e){e.set({stroke:"",strokeWidth:0})}function S(e,r="color",n="",o=!1){if(e&&s.current_image_url!=e){if(s.current_image_url=e,console.log("imageURL: ",e),console.log("backImageURL: ",n),r==="pos"){s.current_image_side=="front"?s.current_image_side="back":s.current_image_side="front",console.log("changing side to:  ",s.current_image_side);let l=s.current_image_side=="front"?A:E,c=localStorage.getItem(l);if(!c)Array.from(h.text_container.children).forEach(i=>{i.id!=="addTextInput"&&i.remove()}),t.getObjects().forEach(i=>{i.stay_when_pos||i.type==="rect"||i.type==="group"||t.remove(i),i.type=="rect"&&Q(i)}),fabric.Image.fromURL(e,function(i){let a=Math.min(t.width/i.width,t.height/i.height);i.set({product_image:!0,left:t.width/2,top:t.height/2,originX:"center",originY:"center",scaleX:a,scaleY:a,selectable:!1,hasControls:!1,excludeFromClipping:!0}),t.add(i),t.sendToBack(i),t.requestRenderAll(),localStorage.setItem(e,JSON.stringify(t))});else{t.clear(),h.text_container&&(console.log("here??"),Array.from(h.text_container.children).forEach(a=>{a.id!=="addTextInput"&&a.remove()}));let i=0;t.loadFromJSON(c,function(){t.requestRenderAll(),u={},h.text_container&&(h.text_container.innerHTML=""),t.getObjects().forEach(d=>{if(d.type=="rect",d._originalElement&&d._originalElement.src.includes("color"),d.type=="image"&&d._originalElement&&d._originalElement.src.includes("clipart"),d.type==="textbox"){d.input_id||(i++,d.input_id="text_"+i),u[d.input_id]=d,u[d.input_id].controls={...fabric.Object.prototype.controls,deleteControl:fabric.Object.prototype.controls.deleteControl};let _=document.getElementById(d.input_id);console.log("existingInput: ",_),_?_.value=d.text:ce(d.input_id,d.text)}});const a=document.querySelectorAll(".dynamic-text-input");console.log("dynamicInputs: ",a),a.length>0&&M(Array.from(a)),H(u),Z()}),fabric.Image.fromURL(e,function(a){let d=Math.min(t.width/a.width,t.height/a.height);a.set({product_image:!0,left:t.width/2,top:t.height/2,originX:"center",originY:"center",scaleX:d,scaleY:d,selectable:!1,hasControls:!1,excludeFromClipping:!0}),t.add(a),t.sendToBack(a),t.requestRenderAll()})}return}if(r=="color"){if(o){let l=s.current_image_side=="front"?A:E,c=localStorage.getItem(l);c&&t.loadFromJSON(c),fabric.Image.fromURL(e,function(i){let a=Math.min(t.width/i.width,t.height/i.height);i.set({product_image:!0,left:t.width/2,top:t.height/2,originX:"center",originY:"center",scaleX:a,scaleY:a,selectable:!1,hasControls:!1,excludeFromClipping:!0}),t.add(i),t.sendToBack(i),t.requestRenderAll()});return}ee(e,n);return}}}function ee(e,r){ne();let n=s.current_image_side=="front"?e:r;s.current_image_url=n,fabric.Image.fromURL(n,function(o){let l=Math.min(t.width/o.width,t.height/o.height);o.set({product_image:!0,left:t.width/2,top:t.height/2,originX:"center",originY:"center",scaleX:l,scaleY:l,selectable:!1,hasControls:!1,excludeFromClipping:!0}),t.add(o),t.sendToBack(o),t.requestRenderAll()})}function te(){document.addEventListener("keydown",function(e){if(e.key==="Delete"){let r=t.getActiveObject();if(r&&r.type==="textbox"){const n=document.getElementById(r.input_id),o=n.closest(".text-input-group").querySelector(".remove-text-btn");o&&n&&n.id==r.input_id&&(n.remove(),o.remove()),t.remove(r),t.requestRenderAll(),f(),g(s.current_image_url)}else r&&(t.remove(r),t.requestRenderAll(),f())}})}function ne(){t.getObjects().forEach(e=>{e.product_image&&t.remove(e)})}function re(e){e.forEach(r=>{r.addEventListener("click",()=>{if(!m){alert("Please select a text object first");return}const n=r.getAttribute("data-style"),o={bold:()=>I("fontWeight","bold","normal"),italic:()=>I("fontStyle","italic","normal"),underline:()=>I("underline",!0,!1),shadow:()=>I("shadow","2px 2px 5px rgba(0,0,0,0.3)",""),curved:()=>oe(m),normal:()=>m.set({fontWeight:"normal",fontStyle:"normal",underline:!1,shadow:"",path:null})};o[n]&&o[n](),t.requestRenderAll()})})}function I(e,r,n){m.set(e,m[e]===r?n:r)}function oe(e){if(!e||e.type!=="textbox"){alert("Please select a text object.");return}let r=e.text||" ",n=80,o=Math.max(5,150/r.length);e.set("path",null);let l=new fabric.Path(`M 0,${n} A ${n},${n/1.5} 0 1,1 ${n*2},${n}`,{fill:"",stroke:"",selectable:!1,evented:!1});e.set({path:l,pathSide:"top",pathAlign:"center",charSpacing:o*10,originX:"center",left:t.width/2}),t.requestRenderAll(),f(),g(s.current_image_url)}function ie(e){e.addEventListener("input",r=>{m&&(m.set("fontSize",parseInt(e.value)),t.requestRenderAll(),f(),g(s.current_image_url))})}function le(e){e.addEventListener("click",r=>{e.showPicker()}),e.addEventListener("change",r=>{m&&(m.set("fill",e.value),t.requestRenderAll(),f(),g(s.current_image_url))})}function ae(e){e.addEventListener("change",r=>{m&&(m.set("fontFamily",e.value),t.requestRenderAll(),f(),g(s.current_image_url))})}function M(e){console.log("inputs in handleTextInputs: ",e);let r=D(t);for(let n of e)n&&n.addEventListener("input",o=>{if(u[n.id])u[n.id].set({text:n.value}),t.setActiveObject(u[n.id]),m=u[n.id],t.requestRenderAll(),f();else{const l=Object.keys(u).length,c=t.height*.2,i=t.height/2-c/2,a=l%5,d=i+c*(a+1)/6,_=r.text;u[n.id]=new fabric.Textbox("",{left:t.width/2,input_id:n.id,top:d,originX:"center",originY:"center",textAlign:"center",selectable:!0,evented:!0,..._}),u[n.id].controls={...fabric.Object.prototype.controls,deleteControl:fabric.Object.prototype.controls.deleteControl},t.add(u[n.id]),u[n.id].set({text:n.value}),t.setActiveObject(u[n.id]),m=u[n.id],t.requestRenderAll(),f()}})}function H(e){console.log("objects in handleInlineTextInputs: ",e),t.on("text:changed",r=>{let n=r.target;if(n.input_id){const o=document.getElementById(n.input_id);console.log("input: ",o),o&&(o.value=n.text)}g(s.current_image_url),f()})}function ce(e,r){if(!h.text_container)return;const n=`
        <div class="text-input-group d-flex align-items-center gap-2" data-input-id="${e}">
            <div class="input-wrapper flex-grow-1">
                <input type="text" id="${e}" class="form-control input-styled my-4 dynamic-text-input" placeholder="შეიყვანე ტექსტი" value="${r||""}">
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-text-btn">✕</button>
        </div>
    `;h.text_container.insertAdjacentHTML("beforeend",n);const o=document.getElementById(e);o&&o.closest(".text-input-group").querySelector(".remove-text-btn").addEventListener("click",function(){u[e]&&(t.remove(u[e]),delete u[e],t.requestRenderAll(),f(),g(s.current_image_url)),o.closest(".text-input-group").remove()})}function se(){const r=document.querySelectorAll(".color-option")[0],n=r.getAttribute("data-front-image"),o=r.getAttribute("data-back-image");b=n.includes("color")?n:null,p=o.includes("color")?o:null,s.front_image_url=n,s.back_image_url=p,de(n).then(()=>{const l=A,c=localStorage.getItem(l);requestAnimationFrame(c?()=>{S(n,"color","",!0),R()}:()=>{S(n,"color",p),R()})})}function de(e){return new Promise((r,n)=>{const o=new Image;o.onload=()=>r(o),o.onerror=n,o.src=e})}function ue(){fe(),ge()}function fe(){document.querySelectorAll(".clipart-img").forEach(r=>{r.addEventListener("click",he)}),document.querySelector("#clipartCategory").addEventListener("change",me)}function ge(){const e=document.querySelector("#uploadSidebar"),r=document.querySelector("#toggleUploadSidebar"),n=document.querySelector("#closeUploadSidebar");let o=document.querySelector("#uploaded_image");r.addEventListener("click",l=>z(l,e)),n.addEventListener("click",l=>{z(l,e,0)}),o.addEventListener("change",function(l){let c=new FileReader;c.onload=function(){let i=document.createElement("img");i.src=c.result,document.querySelector("#imagePreviewContainer").innerHTML="",document.querySelector("#imagePreviewContainer").appendChild(i)},c.readAsDataURL(l.target.files[0])}),document.querySelector("#imagePreviewContainer")&&document.querySelector("#imagePreviewContainer").addEventListener("click",function(l){let i=l.target.src;i&&fabric.Image.fromURL(i,function(a){let d=t.width*.4,_=t.height*.4,w=Math.min(d/a.width,_/a.height);a.set({left:t.width/2-a.width*w/2,top:t.height/2-a.height*w/2,scaleX:w,scaleY:w,selectable:!0}),a.controls={...fabric.Object.prototype.controls,deleteControl:fabric.Object.prototype.controls.deleteControl},t.add(a),t.setActiveObject(a),f(),g(s.current_image_url)})})}function he(){let e=this.getAttribute("data-image");fabric.Image.fromURL(e,function(r){let n=t.width*.4,o=t.height*.4,l=Math.min(n/r.width,o/r.height);r.set({left:t.width/2-r.width*l/2,top:t.height/2-r.height*l/2,scaleX:l,scaleY:l,selectable:!0,hasControls:!0,stay:!0}),r.controls={...fabric.Object.prototype.controls,deleteControl:fabric.Object.prototype.controls.deleteControl},t.add(r),t.setActiveObject(r),t.requestRenderAll(),g(s.current_image_url)})}function me(e){let r=e.target.value;document.querySelectorAll(".clipart-img").forEach(o=>{r==="all"||o.dataset.category===r?o.style.display="block":o.style.display="none"})}function X(e){t.setWidth(B.clientWidth),t.setHeight(B.clientWidth*1.5)}function f(){let e=s.current_image_side=="front"?A:E,r=t.toJSON();r.objects=r.objects.filter(n=>!n.src||!n.src.includes("color")),localStorage.setItem(e,JSON.stringify(r))}function g(e){localStorage.setItem(e,JSON.stringify(t))}let v={front_image:"",back_image:"",front_assets:"",back_assets:""};function pe(){document.querySelector("#addToCart").addEventListener("click",async function(e){e.preventDefault();const r=s.current_image_side;try{await O(r),p?r==="front"?(S(p,"pos"),setTimeout(async()=>{await O("back"),L()},500)):r==="back"&&(S(b,"pos"),setTimeout(async()=>{await O("front"),L()},500)):L()}catch(n){alert("Failed to save design before adding to cart."),console.error(n)}})}function O(e){return new Promise((r,n)=>{try{t.setZoom(1),t.setViewportTransform([1,0,0,1,0,0]),t.requestRenderAll();const o=e==="front"?A:E;localStorage.setItem(o,JSON.stringify(t.toJSON()));const l=new fabric.Canvas(null,{width:t.width,height:t.height}),i=t.getObjects().filter(a=>a.type!=="rect"&&a.type!=="group"&&!(a!=null&&a.product_image)).map(a=>a.toObject());fabric.util.enlivenObjects(i,function(a){a.forEach(y=>l.add(y)),l.requestRenderAll();const d=l.toDataURL({format:"png",quality:1,backgroundColor:"transparent"});e==="front"?v.front_assets=d:v.back_assets=d,l.dispose();const _=[];t.getObjects().forEach(y=>{(y.type==="rect"||y.type==="group")&&(_.push(y),t.remove(y))});const w=t.toDataURL({format:"png",quality:1});_.forEach(y=>{y.set({selectable:!1,hasControls:!1,evented:!1,stay:!0,stay_when_pos:!0}),C(y)}),t.requestRenderAll(),e==="front"?v.front_image=w:v.back_image=w,r()})}catch(o){console.error("Error saving design:",o),n(o)}})}function L(){if(!v.front_image){alert("Please save your design first");return}const e=v.back_image||null,r=v.front_assets||null,n=v.back_assets||null,l=document.querySelector("#sizeSelect").value;let c={front_image:v.front_image,back_image:e,front_assets:r,back_assets:n,product_id:k.getAttribute("data-id"),v_hash:localStorage.getItem("v_hash"),quantity:localStorage.getItem("quantity")||1,price:null,default_img:0,size:l},i=new FormData;i.append("front_image",c.front_image),i.append("front_assets",c.front_assets),i.append("product_id",c.product_id),i.append("v_hash",c.v_hash),i.append("quantity",c.quantity),i.append("default_img",c.default_img),e&&i.append("back_image",e),n&&i.append("back_assets",n),console.log("form: ",c),axios.post("/cart",i).then(a=>{alert("Item successfully added to cart");let d=document.getElementById("cart-count").textContent;console.log("count is: ",d),d++,document.getElementById("cart-count").textContent=d}).catch(a=>{console.error("Error adding to cart:",a),alert("There was an error adding the item to cart")})}export{_e as default};
