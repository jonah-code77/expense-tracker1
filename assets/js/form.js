

class app {
    constructor(formId, msgId, file){
        this.form = document.getElementById(formId);
        this.msg = document.getElementById(msgId);
        this.file = file;
        this.init();
    }

   showMessage(msg, type = 'info'){
    this.msg.innerHTML = `<p class="${type}">${msg}</p>`;
   }

    submitForm() {
        fetch(this.file, {
            method: "POST",
            body: new FormData(this.form) 
        })                          
        .then(res => {
            const contentType = res.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error(`Server returned non-JSON response (${res.status})`);
            }
            return res.json();
        })         
        .then(data => {
            if (data.status === "success") {

                if (data.redirect) {
                     window.location.href = data.redirect;     
                }
            }else {
                const errorMsg = typeof data.msg === "object"
                    ? Object.entries(data.msg).map(([field, msg]) =>
                         `${field}: ${msg}`).join("<br>")
                    : data.msg;

                this.showMessage(errorMsg, "error");
            }
        })
        .catch(err => {
            this.showMessage("Something went wrong. Try again.", "error");
            console.error(err);
        });
    }

    init(){
        if(!this.form || !this.msg)return;
        this.form.addEventListener("submit",e =>{
            e.preventDefault();
            this.submitForm();
        })
    }
}

new app("regForm", "msg", `register`)
new app("logIn", "msg", "login");



