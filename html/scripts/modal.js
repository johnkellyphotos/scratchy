(function () {
    class Modal {
        modalButtonActions = {
            Yes: 'Yes',
            No: 'No',
            Okay: 'Okay',
            Cancel: 'Cancel',
        };

        post = (url, data = {}) => {
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

            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new URLSearchParams(encode(data)).toString(),
                credentials: 'same-origin',
            }).then(r => r.text());
        };

        launch = (url, postData = null) => {
            const req = postData ? this.post(url, postData) : fetch(url).then(r => r.text());

            req
                .then(html => {
                    const container = document.createElement('div');
                    container.innerHTML = html;
                    document.body.appendChild(container);

                    const modalEl = container.querySelector('.modal');
                    const modal = new mdb.Modal(modalEl);

                    const cleanup = () => {
                        try {
                            modal.dispose?.();
                        } catch (e) {
                        }
                        container.remove();
                    };

                    modalEl.addEventListener('hidden.mdb.modal', cleanup, {once: true});

                    modalEl.querySelectorAll('[data-app-modal-action]').forEach(modalButton => {
                        modalButton.addEventListener('click', () => {
                            const action = modalButton.dataset.appModalAction;
                            const shouldReload = parseInt(modalButton.dataset.appModalReload, 10) === 1;

                            if (
                                action !== this.modalButtonActions.No &&
                                action !== this.modalButtonActions.Cancel &&
                                action !== this.modalButtonActions.Okay
                            ) {
                                let inputsData = {}
                                modalEl.querySelectorAll('input, select, textarea').forEach(inputEl => {
                                    inputsData[inputEl.name] = inputEl.value
                                })

                                this.launch(url, {modalButtonAction: action, modalInputData: inputsData});
                            }

                            modal.hide();

                            if (shouldReload) {
                                window.location.reload();
                            }
                        });
                    });

                    modal.show();
                })
                .catch(err => console.error(err));
        };
    }

    window.Modal = new Modal();
})();
