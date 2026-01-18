(function () {
    class Modal {
        modalButtonActions = {
            Yes: 'Yes',
            No: 'No',
            Okay: 'Okay',
            Cancel: 'Cancel',
        }
        launch = (url) => {
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const container = document.createElement('div');
                    container.innerHTML = html;
                    document.body.appendChild(container);

                    const modalEl = container.querySelector('.modal');
                    const modal = new mdb.Modal(modalEl);

                    modalEl.querySelectorAll('[data-app-modal-action]').forEach(modalButton => {
                        modalButton.addEventListener('click', () => {
                            if (
                                modalButton.dataset.appModalAction === this.modalButtonActions.No ||
                                modalButton.dataset.appModalAction === this.modalButtonActions.Cancel ||
                                modalButton.dataset.appModalAction === this.modalButtonActions.Okay
                            ) {
                                // do nothing
                            } else {
                                this.launch(url + '&modalButtonAction=' + modalButton.dataset.appModalAction)
                            }
                            modal.hide();

                            if (parseInt(modalButton.dataset.appModalReload) === 1) {
                                window.location.reload()
                            }
                        })
                    })

                    modal.show();
                })
                .catch(err => {
                    console.error(err);
                });
        }
    }

    window.Modal = new Modal()

})();