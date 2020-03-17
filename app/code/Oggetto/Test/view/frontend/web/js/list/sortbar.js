/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
        'jquery',
        'jquery-ui-modules/widget'
    ], function ($) {
        'use strict';

        /**
         * ProductListToolbarForm Widget - this widget is setting cookie and submitting form according to toolbar controls
         */
        $.widget('mage.productListToolbarForm', {

            options: {
                select: '[name="sorter"]',
                url: '',
                sort: 'sort',
                default: ''
            },

            /** @inheritdoc */
            _create: function () {
                console.log($(this.options.select), this.options.sort, this.options.default)
                this._bind($(this.options.select), this.options.sort, this.options.default);
            },

            /** @inheritdoc */
            _bind: function (element, paramName, defaultValue) {
                if (element.is('select')) {
                    console.log(defaultValue);
                    element.val(defaultValue);
                    element.on('change', {
                        paramName: paramName,
                        default: defaultValue
                    }, $.proxy(this._processSelect, this));
                }
            },

            /**
             * @param {jQuery.Event} event
             * @private
             */
            _processSelect: function (event) {
                this.changeUrl(
                    event.data.paramName,
                    event.currentTarget.options[event.currentTarget.selectedIndex].value
                );
            },

            /**
             * @param {String} paramName
             * @param {*} paramValue
             */
            changeUrl: function (paramName, paramValue) {
                var decode = window.decodeURIComponent,
                    urlPaths = this.options.url.split('?'),
                    baseUrl = urlPaths[0],
                    urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                    paramData = {},
                    parameters, i, form, params, key, input, formKey;

                for (i = 0; i < urlParams.length; i++) {
                    parameters = urlParams[i].split('=');
                    paramData[decode(parameters[0])] = parameters[1] !== undefined ?
                        decode(parameters[1].replace(/\+/g, '%20')) :
                        '';
                }
                paramData[paramName] = paramValue;

                paramData = $.param(paramData);
                location.href = baseUrl + (paramData.length ? '?' + paramData : '');
            }
        });

        return $.mage.productListToolbarForm;
    }
);
