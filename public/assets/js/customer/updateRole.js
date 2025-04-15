const hiddenMsgOwner = document.getElementById('hidden_msg_owner');
const hiddenMsgSerPro = document.getElementById('hidden_msg_serpro');
const closeOwner = document.getElementById('close_owner');
const closeSerPro = document.getElementById('close_serpro');


const updateSerProBtn = document.getElementById('serpro_btn');
const updateOwnerBtn = document.getElementById('owner_btn');

if (updateOwnerBtn) {
    updateOwnerBtn.addEventListener('click', () => {
        hiddenMsgOwner.style.display = 'block';
    });
}

if (updateSerProBtn) {
    updateSerProBtn.addEventListener('click', () => {
        hiddenMsgSerPro.style.display = 'block';
    });
}

if (closeOwner) {
    closeOwner.addEventListener('click', () => {
        hiddenMsgOwner.style.display = 'none';
    });
}

if (closeSerPro) {
    closeSerPro.addEventListener('click', () => {
        hiddenMsgSerPro.style.display = 'none';
    });
}
