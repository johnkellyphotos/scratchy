(function () {
    const encode = (obj) => {
        const out = {};
        for (const [k, v] of Object.entries(obj ?? {})) {
            if (v === undefined) {
                continue;
            }

            if (v !== null && typeof v === 'object') {
                out[k] = JSON.stringify(v);
                continue;
            }

            out[k] = v;
        }
        return out;
    };

    const messageEl = document.querySelector('#message_input')
    console.log(document.querySelector('#btn-send-message'))
    document.querySelector('#btn-send-message').addEventListener('click', () => {
        let message = messageEl.value
        if (!message) {

        }
        messageEl.value = ''
        fetch('/Api/remote/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams(encode({message: message})).toString(),
            credentials: 'same-origin'
        }).then(r => r.json())
            .then(json => {
                console.log(json)
            });
    })

    document.querySelectorAll('[data-remote-button]').forEach(remoteButtonEl => {
        remoteButtonEl.addEventListener('click', () => {
            let data = {
                button: remoteButtonEl.dataset.remoteButton
            }

            let oldBorder = remoteButtonEl.style.border
            remoteButtonEl.style.border = "4px solid black"
            window.setTimeout(() => remoteButtonEl.style.border = oldBorder, 2000)

            fetch('/Api/remote/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(encode(data)).toString(),
                credentials: 'same-origin'
            }).then(r => r.json())
                .then(json => {
                    console.log(json)
                });

        })
    })
})();