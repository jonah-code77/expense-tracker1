

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
        .then(res => res.json())          
        .then(data => {
            if (data.status === "success") {
                this.showMessage(data.msg, "success"); 

                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);  
                }
            }else {
                const errorMsg = typeof data.msg === "object"
                    ? Object.values(data.msg).join("<br>")
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

new app("regForm", "msg", `registerr`)
new app("logIn", "msg", "logInn");



