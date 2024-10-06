// Add following div where search bar should be

/* <div id="addressSearchBar" ref="addressSearchBar" class="form-control" style="z-index: 9 !important"></div> */

// Add a wrapping handler for storing address 
/**
 * Wraps address setting to store
 * @param {string} address address to handle
 */
//handleAddress(address)

export default {
    methods: {
        setupAddressSearchBar() {
            // src: https://inaadress.maaamet.ee/inaadress/pdf/en/in_aadress_developer_manual.pdf
            var inAadress = new InAadress({ "container": "addressSearchBar", "mode": "3", "nocss": false, "lang": "en", "appartment": 2, "ihist": "0" });
            let self = this;
            document.addEventListener('addressSelected', function(e) {
                inAadress.hideResult();
                var aadress = e.detail[0].paadress;
                inAadress.setAddress(aadress);
                self.handleAddress(aadress);
            });
        },
    }
}