const settings = window.wc.wcSettings.getSetting('meezanbank_data', {});


const label = Object(window.wp.element.createElement)('div', { className: 'custom-payment-label' },


    // Add the image icon  


    Object(window.wp.element.createElement)(


        'img',


        {


            src: settings.icon,


            alt: 'Meezan Icon',


            width: '30px',


            style: { float: 'left' }


        }


    ),


    // Add the label text  


    Object(window.wp.element.createElement)('span', null, settings.title || 'Meezanbank'),


);


const Content = () => {


    return window.wp.htmlEntities.decodeEntities(settings.description || '');


};


const Block_Gateway = {


    name: 'class_meezan_bank',


    label: label,


    content: Object(window.wp.element.createElement)(Content, null),


    edit: Object(window.wp.element.createElement)(Content, null),


    canMakePayment: () => true,


    ariaLabel: settings.title || 'Meezanbank',


    supports: {


        features: settings.supports,


    },


};


window.wc.wcBlocksRegistry.registerPaymentMethod(Block_Gateway);


