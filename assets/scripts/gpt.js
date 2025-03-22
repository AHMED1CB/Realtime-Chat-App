const sendMsgBtn = document.querySelector('.send i');
const input  = document.querySelector('.message-content-inp'); 
const msgsContainer  = document.querySelector('.messages'); 
let state = 'done'; 
msgsContainer.scrollTop = msgsContainer.scrollHeight ;


sendMsgBtn.onclick = () => {   
    if (input.value.trim() && state === 'done') {

        const gptURi = '/ai/gpt/';
        let msg = input.value.trim();
        const query = new FormData();
        query.set('query', msg);
        
        createMessage('right', msg);

        const data = {
            method: "POST",
            body: query
        };

        msgsContainer.scrollTop = msgsContainer.scrollHeight ;
        state = 'insent';
        fetch(gptURi, data).then(e => e.json()).then(e => {
          
          let messageResponse = e.choices[0].message.content.split(' ');
            let msgId = e.id;
            createMessage('left', messageResponse[0], msgId);
            
            let i = 1;
            function addWord() {
                if (i < messageResponse.length) {
                    setMsgCont(msgId, ' ' + messageResponse[i]);
                    i++;
                    setTimeout(addWord, 300);
                }else{
                    state = 'done';
               }
           msgsContainer.scrollTop = msgsContainer.scrollHeight ;

            }

            addWord();
        }).finally(() => {
           msgsContainer.scrollTop = msgsContainer.scrollHeight ;
        }).catch(() => {
             alert('Some thing went Wrong');
        });
        input.value = '';
    
    }

}

function createMessage(pos, msg, uniqueId = 'client') {
    let msgTmp = `
        <div class="message message-${pos}" data-msg="${uniqueId}">
            <div class="msg-cont  <?=$msg['state'] === 'deleted' ? 'deleted' : '' ?>">
                <div class="cont" style="min-width:100px;padding:10px 30px"> ${msg}  </div>
            </div>         
        </div>
    `;
    msgsContainer.innerHTML += msgTmp;
}

function setMsgCont(msgId, cont) {
    let msg = document.querySelector(`[data-msg=${msgId}]`);
    msg.querySelector('.cont').innerHTML += cont; 
}
