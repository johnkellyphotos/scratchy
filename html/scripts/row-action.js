(function () {

    window.loadRowActions = () => {
        document.querySelectorAll('[data-app-row-action]').forEach(rowAction => {
            rowAction.addEventListener('click', () => {
                const table = rowAction.closest('table[data-app-table]');
                if (window.Modal && table) {
                    window.Modal.lastTable = table;
                    window.Modal.lastTableModel = table.dataset.appTableModel || null;
                }

                let url = rowAction.dataset.appRowAction;

                let params = {
                    model: rowAction.dataset.appModel,
                    id: rowAction.dataset.appId,
                };

                let query = new URLSearchParams(params).toString();
                url += '?' + query;
                window.Modal.launch(url)
            })
        })
    };


    window.loadRowActions()

})();
