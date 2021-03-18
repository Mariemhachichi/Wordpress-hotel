(function( $ ) {
	$( function() {
		MPHB.DateRules = can.Construct.extend( {}, {
	dates: {},
	init: function( dates ) {
		this.dates = dates;
	},
	/**
	 *
	 * @param {Date} date
	 * @returns {Boolean}
	 */
	canCheckIn: function( date ) {
		var formattedDate = this.formatDate( date );
		if ( !this.dates.hasOwnProperty( formattedDate ) ) {
			return true;
		}
		return !this.dates[formattedDate].not_check_in && !this.dates[formattedDate].not_stay_in;
	},
	/**
	 *
	 * @param {Date} date
	 * @returns {Boolean}
	 */
	canCheckOut: function( date ) {
		var formattedDate = this.formatDate( date );
		if ( !this.dates.hasOwnProperty( formattedDate ) ) {
			return true;
		}
		return !this.dates[formattedDate].not_check_out;
	},
	/**
	 *
	 * @param {Date} date
	 * @returns {Boolean}
	 */
	canStayIn: function( date ) {
		var formattedDate = this.formatDate( date );
		if ( !this.dates.hasOwnProperty( formattedDate ) ) {
			return true;
		}
		return !this.dates[formattedDate].not_stay_in;
	},

	/**
	 *
	 * @param {Date} dateFrom
	 * @param {Date} stopDate
	 * @returns {Date}
	 */
	getNearestNotStayInDate: function( dateFrom, stopDate ) {
		var nearestDate = MPHB.Utils.cloneDate( stopDate );
		var dateFromFormatted = $.datepick.formatDate( 'yyyy-mm-dd', dateFrom );
		var stopDateFormatted = $.datepick.formatDate( 'yyyy-mm-dd', stopDate );

		$.each( this.dates, function( ruleDate, rule ) {
			if ( ruleDate > stopDateFormatted ) {
				return false;
			}
			if ( dateFromFormatted > ruleDate ) {
				return true;
			}
			if ( rule.not_stay_in ) {
				nearestDate = $.datepick.parseDate( 'yyyy-mm-dd', ruleDate );
				return false;
			}
		} );
		return nearestDate;
	},
	/**
	 *
	 * @param {Date} date
	 * @returns {string}
	 */
	formatDate: function( date ) {
		return $.datepick.formatDate( 'yyyy-mm-dd', date );
	}
} );

/**
 * @class MPHB.Datepicker
 */
can.Control( 'MPHB.Datepicker', {}, {
	form: null,
	hiddenElement: null,
	roomTypeId: null,
	init: function( el, args ) {
		this.form = args.form;
		this.roomTypeId = args.hasOwnProperty('roomTypeId') ? args.roomTypeId : 0;
		this.setupHiddenElement();
		this.initDatepick();
	},
	setupHiddenElement: function() {
		var hiddenElementId = this.element.attr( 'id' ) + '-hidden';
		this.hiddenElement = $( '#' + hiddenElementId );

		// fix date
		if ( !this.hiddenElement.val() ) {
//			this.element.val( '' );
		} else {
			var date = $.datepick.parseDate( MPHB._data.settings.dateTransferFormat, this.hiddenElement.val() );
			var fixedValue = $.datepick.formatDate( MPHB._data.settings.dateFormat, date );
			this.element.val( fixedValue );
		}
	},
	initDatepick: function() {
		var defaultSettings = {
			dateFormat: MPHB._data.settings.dateFormat,
			altFormat: MPHB._data.settings.dateTransferFormat,
			altField: this.hiddenElement,
			minDate: MPHB.HotelDataManager.myThis.today,
			monthsToShow: MPHB._data.settings.numberOfMonthDatepicker,
			firstDay: MPHB._data.settings.firstDay,
			pickerClass: MPHB._data.settings.datepickerClass,
            useMouseWheel: false,
		};
		var datepickSettings = $.extend( defaultSettings, this.getDatepickSettings() );
		this.element.datepick( datepickSettings );
	},
	/**
	 *
	 * @returns {Object}
	 */
	getDatepickSettings: function() {
		return {};
	},
	/**
	 * @return {Date|null}
	 */
	getDate: function() {
		var dateStr = this.element.val();
		var date = null;
		try {
			date = $.datepick.parseDate( MPHB._data.settings.dateFormat, dateStr );
		} catch ( e ) {
			date = null;
		}
		return date;
	},
	/**
	 *
	 * @param {string} format Optional. Datepicker format by default.
	 * @returns {String} Date string or empty string.
	 */
	getFormattedDate: function( format ) {
		if ( typeof (format) === 'undefined' ) {
			format = MPHB._data.settings.dateFormat;
		}
		var date = this.getDate();
		return date ? $.datepick.formatDate( format, date ) : '';
	},
	/**
	 * @param {Date} date
	 */
	setDate: function( date ) {
		this.element.datepick( 'setDate', date );
	},
	/**
	 * @param {string} option
	 */
	getOption: function( option ) {
		return this.element.datepick( 'option', option );
	},
	/**
	 * @param {string} option
	 * @param {mixed} value
	 */
	setOption: function( option, value ) {
		this.element.datepick( 'option', option, value );
	},
	/**
	 *
	 * @returns {Date|null}
	 */
	getMinDate: function() {
		var minDate = this.getOption( 'minDate' );
		return minDate !== null && minDate !== '' ? MPHB.Utils.cloneDate( minDate ) : null;
	},
	/**
	 *
	 * @returns {Date|null}
	 */
	getMaxDate: function() {
		var maxDate = this.getOption( 'maxDate' );
		return maxDate !== null && maxDate !== '' ? MPHB.Utils.cloneDate( maxDate ) : null;
	},

	/**
	 *
	 * @returns {Date|null}
	 */
	getMaxAdvanceDate: function() {
		var maxAdvanceDate = this.getOption( 'maxAdvanceDate' );
		return maxAdvanceDate ? MPHB.Utils.cloneDate( maxAdvanceDate ) : null;
	},
	/**
	 *
	 * @returns {undefined}
	 */
	clear: function() {
		this.element.datepick( 'clear' );
	},
	/**
	 * @param {Date} date
	 * @param {string} format Optional. Default 'yyyy-mm-dd'.
	 */
	formatDate: function( date, format ) {
		format = typeof (format) !== 'undefined' ? format : 'yyyy-mm-dd';
		return $.datepick.formatDate( format, date );
	},
	/**
	 *
	 * @returns {undefined}
	 */
	refresh: function() {
		$.datepick._update( this.element[0], true );
		$.datepick._updateInput( this.element[0], false );
	}

} );


MPHB.FlexsliderGallery = can.Control.extend( {}, {
	sliderEl: null,
	navSliderEl: null,
	groupId: null,
	init: function( sliderEl, args ) {
		this.sliderEl = sliderEl;

		this.groupId = sliderEl.data( 'group' );

		var navSliderEl = $( '.mphb-gallery-thumbnail-slider[data-group="' + this.groupId + '"]' );

		if ( navSliderEl.length ) {
			this.navSliderEl = navSliderEl;
		}

		var self = this;

		$( window ).on( 'load', function() {
			self.initSliders();
		} );

		// Load immediately is the window already loaded
		if ( document.readyState == 'complete' ) {
			this.initSliders();
		}
	},
	initSliders: function() {
		if ( this.slidersLoaded ) {
			return;
		}

		var sliderAtts = this.sliderEl.data( 'flexslider-atts' );

		if ( this.navSliderEl ) {
			var navSliderAtts = this.navSliderEl.data( 'flexslider-atts' );
			navSliderAtts['asNavFor'] = '.mphb-flexslider-gallery-wrapper[data-group="' + this.groupId + '"]';
			navSliderAtts['itemWidth'] = this.navSliderEl.find( 'ul > li img' ).width()

			sliderAtts['sync'] = '.mphb-gallery-thumbnail-slider[data-group="' + this.groupId + '"]';

			// The slider being synced must be initialized first
			this.navSliderEl
				.addClass( 'flexslider mphb-flexslider mphb-gallery-thumbnails-slider' )
				.flexslider( navSliderAtts );
		}

		this.sliderEl
			.addClass( 'flexslider mphb-flexslider mphb-gallery-slider' )
			.flexslider( sliderAtts );

		this.slidersLoaded = true;
	}
} );

/**
 * @see MPHB.format_price() in admin/admin.js
 */
MPHB.format_price = function( price, atts ) {
	atts = atts || {};

	var defaultAtts = MPHB._data.settings.currency;
	atts = $.extend( { 'trim_zeros': false }, defaultAtts, atts );

	price = MPHB.number_format( price, atts['decimals'], atts['decimal_separator'], atts['thousand_separator'] );
	var formattedPrice = atts['price_format'].replace( '%s', price );

	if ( atts['trim_zeros'] ) {
		var regex = new RegExp('\\' + atts['decimal_separator'] + '0+$|(\\' + atts['decimal_separator'] + '\\d*[1-9])0+$');
		formattedPrice = formattedPrice.replace( regex, '$1' );
	}

	var priceHtml = '<span class="mphb-price">' + formattedPrice + '</span>';

	return priceHtml;
};

/**
 * @see MPHB.number_format() in admin/admin.js
 */
MPHB.number_format = function( number, decimals, dec_point, thousands_sep ) {
	// + Original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// + Improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   Bugfix by: Michael White (http://crestidg.com)
	var sign = '', i, j, kw, kd, km;

	// Input sanitation & defaults
	decimals = decimals || 0
	dec_point = dec_point || '.'
	thousands_sep = thousands_sep || ','

	if ( number < 0 ) {
		sign = '-';
		number *= -1;
	}

	i = parseInt( number = ( +number || 0 ).toFixed( decimals ) ) + '';

	if ( ( j = i.length ) > 3 ) {
		j = j % 3;
	} else {
		j = 0;
	}

	km = ( j ? i.substr( 0, j ) + thousands_sep : '' );
	kw = i.substr( j ).replace( /(\d{3})(?=\d)/g, '$1' + thousands_sep );
	kd = ( decimals ? dec_point + Math.abs( number - i ).toFixed( decimals ).replace( /-/, 0 ).slice( 2 ) : '' );

	return sign + km + kw + kd;
};

/**
 * @param {String} action Action name (without prefix "mphb_").
 * @param {Object} data
 * @param {Object} callbacks "success", "error", "complete".
 * @returns {Object} The jQuery XMLHttpRequest object.
 *
 * @since 3.6.0
 */
MPHB.post = function (action, data, callbacks)
{
    action = 'mphb_' + action;

    data = $.extend(
        {
            action: action,
            mphb_nonce: MPHB._data.nonces[action],
            lang: MPHB._data.settings.currentLanguage
        },
        data
    );

    var ajaxArgs = $.extend(
        {
            url: MPHB._data.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: data
        },
        callbacks
    );

    return $.ajax(ajaxArgs);
}

/**
 * @class MPHB.Season
 */
can.Construct('MPHB.Season', {}, {
	/**
	 * @var {Date}
	 */
	startDate: null,
	/**
	 * @var {Date}
	 */
	endDate: null,
	/**
	 * @var {number[]}
	 */
	allowedDays: [],

	/**
	 *
	 * @param {{start_date: string, end_date: string, allowed_days: Array}} data
	 */
	init: function(data) {
		var dateFormat = MPHB._data.settings.dateTransferFormat;
		this.startDate = $.datepick.parseDate(dateFormat, data.start_date);
		this.endDate = $.datepick.parseDate(dateFormat, data.end_date);
		this.allowedDays = data.allowed_days;
	},

	/**
	 * Check is season contain date
	 *
	 * @param {Date} date
	 * @return {boolean}
	 */
	isContainDate: function(date) {
		return date >= this.startDate
			&& date <= this.endDate
			&& MPHB.Utils.inArray(date.getDay(), this.allowedDays);
	}

});
MPHB.ReservationRulesChecker = can.Construct.extend({
	myThis: null,
}, {
	rules: {
		checkInDays: {},
		checkOutDays: {},
		minStay: {},
		maxStay: {},
		minAdvance: {},
		maxAdvance: {}
	},
	init: function(rulesByTypes) {
		this.rules.checkInDays = $.map(rulesByTypes['check_in_days'], function(ruleDetails) {
			return new MPHB.Rules.CheckInDayRule(ruleDetails);
		});
		this.rules.checkOutDays = $.map(rulesByTypes['check_out_days'], function(ruleDetails) {
			return new MPHB.Rules.CheckOutDayRule(ruleDetails);
		});
		this.rules.minStay = $.map(rulesByTypes['min_stay_length'], function(ruleDetails) {
			return new MPHB.Rules.MinDaysRule(ruleDetails);
		});
		this.rules.maxStay = $.map(rulesByTypes['max_stay_length'], function(ruleDetails) {
			return new MPHB.Rules.MaxDaysRule(ruleDetails);
		});
		this.rules.minAdvance = $.map(rulesByTypes['min_advance_reservation'], function(ruleDetails) {
			return new MPHB.Rules.MinAdvanceDaysRule(ruleDetails);
		});
		this.rules.maxAdvance = $.map(rulesByTypes['max_advance_reservation'], function(ruleDetails) {
			return new MPHB.Rules.MaxAdvanceDaysRule(ruleDetails);
		});

	},
	/**
	 *
	 * @param {string} type
	 * @param {Date} date
	 * @param {int} roomTypeId
	 * @return {*}
	 */
	getActualRule: function(type, date, roomTypeId) {
		var actualRule = null;
		$.each(this.rules[type], function(index, rule) {
			if (rule.isActualRule(date, roomTypeId)) {
				actualRule = rule;
				return false; // break;
			}
		});
		return actualRule;
	},

	getActualCombinedRule: function(type, date) {
		var processedRoomTypes = [];
		var actualRules = [];

		$.each(this.rules[type], function(index, rule) {
			var roomTypes = MPHB.Utils.arrayDiff(rule.roomTypeIds, processedRoomTypes);

			if (!roomTypes.length) {
				return; // continue
			}

			if (!rule.isActualForDate(date)) {
				return; // continue
			}

			actualRules.push(rule);
			processedRoomTypes = processedRoomTypes.concat(roomTypes);

			if (rule.isAllRoomTypeRule()) {
				return false; // break
			}

			// All Room Processed
			if (!MPHB.Utils.arrayDiff(MPHB._data.allRoomTypeIds, processedRoomTypes).length) {
				return false; // break
			}

		});

		return this.combineRules(type, actualRules);
	},

	combineRules: function(type, rules) {
		var rule;
		switch (type) {
			case 'checkInDays':
				var days = [];
				$.each(rules, function(index, rule) {
					days = days.concat(rule.days);
				});
				days = MPHB.Utils.arrayUnique(days);
				rule = new MPHB.Rules.CheckInDayRule({
					season_ids: [0],
					room_type_ids: [0],
					check_in_days: days
				});
				break;
			case 'checkOutDays':
				var days = [];
				$.each(rules, function(index, rule) {
					days = days.concat(rule.days);
				});
				days = MPHB.Utils.arrayUnique(days);
				rule = new MPHB.Rules.CheckOutDayRule({
					season_ids: [0],
					room_type_ids: [0],
					check_out_days: days
				});
				break
			case 'minStay':
				var softRule = MPHB.Utils.arrayMin($.map(rules, function(rule) {
					return rule.min;
				}));
				rule = new MPHB.Rules.MinDaysRule({
					season_ids: [0],
					room_type_ids: [0],
					min_stay_length: softRule
				});
				break;
			case 'maxStay':
				var softRule = MPHB.Utils.arrayMax($.map(rules, function(rule) {
					return rule.max;
				}));
				rule = new MPHB.Rules.MaxDaysRule({
					season_ids: [0],
					room_type_ids: [0],
					max_stay_length: softRule
				});
				break;
			case 'minAdvance':
				var softRule = MPHB.Utils.arrayMin($.map(rules, function(rule) {
					return rule.min;
				}));
				rule = new MPHB.Rules.MinAdvanceDaysRule({
					season_ids: [0],
					room_type_ids: [0],
					min_advance_reservation: softRule
				});
				break;
			case 'maxAdvance':
				var softRule = MPHB.Utils.arrayMax($.map(rules, function(rule) {
					return rule.max;
				}));
				rule = new MPHB.Rules.MaxAdvanceDaysRule({
					season_ids: [0],
					room_type_ids: [0],
					max_advance_reservation: softRule
				});
				break;
		}
		return rule;
	},
	/**
	 *
	 * @param {Date} date
	 * @param {int} roomTypeId
	 * @return {Boolean}
	 */
	isCheckInSatisfy: function(date, roomTypeId) {
		var isSatisfy = false;
		if (roomTypeId) {
			isSatisfy = this.getActualRule('checkInDays', date, roomTypeId).verify(date);
		} else {
			isSatisfy = this.getActualCombinedRule('checkInDays', date).verify(date);
		}
		return isSatisfy;
	},

	/**
	 *
	 * @param {Date} checkInDate
	 * @param {Date} checkOutDate
	 * @param {number} [roomTypeId]
	 * @return {boolean}
	 */
	isCheckOutSatisfy: function(checkInDate, checkOutDate, roomTypeId) {
		var isSatisfy = false;
		if (roomTypeId) {
			isSatisfy = this.getActualRule('checkOutDays', checkInDate, roomTypeId).verify(checkInDate, checkOutDate);
		} else {
			isSatisfy = this.getActualCombinedRule('checkOutDays', checkInDate).verify(checkInDate, checkOutDate);
		}
		return isSatisfy;
	},

	/**
	 *
	 * @param {Date} checkInDate
	 * @param {number} roomTypeId
	 * @return {number}
	 */
	getMinStay: function(checkInDate, roomTypeId) {
		var minStay;
		if (roomTypeId) {
			minStay = this.getActualRule('minStay', checkInDate, roomTypeId).min;
		} else {
			minStay = this.getActualCombinedRule('minStay', checkInDate).min;
		}
		return minStay;
	},

	/**
	 *
	 * @param {Date} checkInDate
	 * @param {number} roomTypeId
	 * @return {number}
	 */
	getMaxStay: function(checkInDate, roomTypeId) {
		var maxStay;
		if (roomTypeId) {
			maxStay = this.getActualRule('maxStay', checkInDate, roomTypeId).max;
		} else {
			maxStay = this.getActualCombinedRule('maxStay', checkInDate).max;
		}
		return maxStay;
	},

    /**
     * @returns {Date}
     *
     * @since 3.8.7
     */
    getToday: function() {
        return MPHB.HotelDataManager.myThis.today;
    },

	/**
	 *
	 * @param {number} roomTypeId
	 * @return {number}
	 */
	 getMinAdvance: function(roomTypeId) {
		var minAdvance;
		if (roomTypeId) {
 			minAdvance = this.getActualRule('minAdvance', this.getToday(), roomTypeId).min;
 		} else {
 			minAdvance = this.getActualCombinedRule('minAdvance', this.getToday()).min;
 		}
 		return minAdvance;
	},

	/**
	 *
	 * @param {number} roomTypeId
	 * @return {number}
	 */
	 getMaxAdvance: function(roomTypeId) {
		var maxAdvance;
		if (roomTypeId) {
 			maxAdvance = this.getActualRule('maxAdvance', this.getToday(), roomTypeId).max;
 		} else {
 			maxAdvance = this.getActualCombinedRule('maxAdvance', this.getToday()).max;
 		}
 		return maxAdvance;
	},

	/**
	 *
	 * @returns {Date}
	 */
	getMinCheckInDate: function(roomTypeId) {
		var minAdvance = this.getMinAdvance(roomTypeId);
		if (minAdvance > 0) {
			return $.datepick.add( MPHB.Utils.cloneDate(this.getToday()), minAdvance, 'd' );
		}
		return MPHB.Utils.cloneDate(this.getToday());
	},

	/**
	 *
	 * @returns {Date}
	 */
	getMaxAdvanceDate: function(roomTypeId) {
		var maxAdvance = this.getMaxAdvance(roomTypeId);
		if (maxAdvance > 0) {
			return $.datepick.add( MPHB.Utils.cloneDate(this.getToday()), maxAdvance, 'd' );
		}
		return null;
	},

	/**
	 *
	 * @param {Date} checkInDate
	 * @returns {Date}
	 */
	getMinCheckOutDate: function(checkInDate, roomTypeId) {
		var minDays = this.getMinStay(checkInDate, roomTypeId);
		return $.datepick.add(MPHB.Utils.cloneDate(checkInDate), minDays, 'd');
	},

	/**
	 *
	 * @param {Date} checkInDate
	 * @returns {Date}
	 */
	getMaxCheckOutDate: function(checkInDate, roomTypeId) {
		var maxDays = this.getMaxStay(checkInDate, roomTypeId);
		return $.datepick.add(MPHB.Utils.cloneDate(checkInDate), maxDays, 'd');
	},

});

MPHB.Rules = {};

MPHB.Rules.BasicRule = can.Construct.extend({}, {
	/**
	 * @var {number[]}
	 */
	seasonIds: [],
	/**
	 * @var {number[]}
	 */
	roomTypeIds: [],

	/**
	 *
	 * @param {{season_ids: number[], room_type_ids: number[]}} data
	 */
	init: function(data) {
		this.seasonIds = data.season_ids;
		this.roomTypeIds = data.room_type_ids;

	},
	/**
	 *
	 * @param {Date} checkInDate
	 * @param {number} roomTypeId
	 * @return {boolean}
	 */
	isActualRule: function(checkInDate, roomTypeId) {
		return this.isActualForRoomType(roomTypeId) && this.isActualForDate(checkInDate);
	},

	/**
	 *
	 * @param {number} roomTypeId
	 * @return {boolean}
	 */
	isActualForRoomType: function(roomTypeId) {
		return MPHB.Utils.inArray(roomTypeId, this.roomTypeIds) || MPHB.Utils.inArray(0, this.roomTypeIds);
	},
	/**
	 *
	 * @param {Date} date
	 * @return {boolean}
	 */
	isActualForDate: function(date) {

		if (this.isAllSeasonRule()) {
			return true;
		}

		var seasonValid = false;
		$.each(this.seasonIds, function(index, seasonId) {
			if (MPHB.HotelDataManager.myThis.seasons[seasonId]
				&& MPHB.HotelDataManager.myThis.seasons[seasonId].isContainDate(date)
			) {
				seasonValid = true;
				return false; // break
			}
		});
		return seasonValid;
	},

	/**
	 *
	 * @param {Date} checkInDate
	 * @param {Date} checkOutDate
	 * @return {boolean}
	 */
	verify: function(checkInDate, checkOutDate) {
		return true;
	},
	/**
	 *
	 * @return {boolean}
	 */
	isAllSeasonRule: function() {
		return MPHB.Utils.inArray(0, this.seasonIds);
	},
	/**
	 *
	 * @return {boolean}
	 */
	isAllRoomTypeRule: function() {
		return MPHB.Utils.inArray(0, this.roomTypeIds);
	},
	/**
	 *
	 * @return {boolean}
	 */
	isGlobalRule: function() {
		return this.isAllSeasonRule() && this.isAllRoomTypeRule();
	}
});

/**
 * @requires ./basic-rule.js
 */
/**
 * @class MPHB.Rules.CheckInDayRule
 */
MPHB.Rules.BasicRule('MPHB.Rules.CheckInDayRule', {}, {
	days: [],
	/**
	 *
	 * @param {{season_ids: number[], room_type_ids: number[], check_in_days: number[]}} data
	 */
	init: function(data) {
		this._super(data);
		this.days = data.check_in_days;
	},
	/**
	 *
	 * @param {Date} checkInDate
	 * @param {Date} [checkOutDate]
	 * @return {boolean}
	 */
	verify: function(checkInDate, checkOutDate) {
		return MPHB.Utils.inArray(checkInDate.getDay(), this.days);
	}
});
/**
 * @requires ./basic-rule.js
 */
/**
 * @class MPHB.Rules.CheckOutDayRule
 */
MPHB.Rules.BasicRule('MPHB.Rules.CheckOutDayRule', {}, {
	days: [],
	/**
	 *
	 * @param {{season_ids: number[], room_type_ids: number[], check_out_days: number[]}} data
	 */
	init: function(data) {
		this._super(data);
		this.days = data.check_out_days;
	},
	/**
	 *
	 * @param {Date} checkInDate
	 * @param {Date} checkOutDate
	 * @return {boolean}
	 */
	verify: function(checkInDate, checkOutDate) {
		return MPHB.Utils.inArray(checkOutDate.getDay(), this.days);
	}
});

/**
 * @requires ./basic-rule.js
 */
/**
 * @class MPHB.Rules.MinDaysRule
 */
MPHB.Rules.BasicRule('MPHB.Rules.MinDaysRule', {}, {
	/**
	 * @var {number}
	 */
	min: null,
	/**
	 *
	 * @param {{season_ids: number[], room_type_ids: number[], min_stay_length: number}} data
	 */
	init: function(data) {
		this._super(data);
		this.min = data.min_stay_length;
	},
	/**
	 *
	 * @param {Date} checkInDate
	 * @param {Date} checkOutDate
	 * @return {boolean}
	 */
	verify: function(checkInDate, checkOutDate) {
		var minCheckOutDate = $.datepick.add(MPHB.Utils.cloneDate(checkInDate), this.min, 'd');
		return MPHB.Utils.formatDateToCompare(checkOutDate) >= MPHB.Utils.formatDateToCompare(minCheckOutDate);
	}
});
/**
 * @requires ./basic-rule.js
 */
/**
 * @class MPHB.Rules.MaxDaysRule
 */
MPHB.Rules.BasicRule('MPHB.Rules.MaxDaysRule', {}, {
	max: null,
	/**
	 *
	 * @param {{season_ids: number[], room_type_ids: number[], max_stay_length: number}} data
	 */
	init: function(data) {
		this._super(data);
		this.max = data.max_stay_length != 0 ? data.max_stay_length : 3652; // 10 years
	},
	/**
	 *
	 * @param {Date} checkInDate
	 * @param {Date} checkOutDate
	 * @return {boolean}
	 */
	verify: function(checkInDate, checkOutDate) {
		var maxCheckOutDate = $.datepick.add(MPHB.Utils.cloneDate(checkInDate), this.max, 'd');
		return MPHB.Utils.formatDateToCompare(checkOutDate) <= MPHB.Utils.formatDateToCompare(maxCheckOutDate);
	}
});
/**
 *
 * @requires ./season.js
 * @requires ./reservation-rules-checker.js
 * @requires ./rules/check-in-day-rule.js
 * @requires ./rules/check-out-day-rule.js
 * @requires ./rules/min-days-rule.js
 * @requires ./rules/max-days-rule.js
 */
/**
 * @class MPHB.HotelDataManager
 */
can.Construct('MPHB.HotelDataManager', {
	myThis: null,
	ROOM_STATUS_AVAILABLE: 'available',
	ROOM_STATUS_NOT_AVAILABLE: 'not-available',
	ROOM_STATUS_BOOKED: 'booked',
	ROOM_STATUS_PAST: 'past',
	ROOM_STATUS_EARLIER_MIN_ADVANCE: 'earlier-min-advance',
	ROOM_STATUS_LATER_MAX_ADVANCE: 'later-max-advance',
	ROOM_STATUS_BOOKING_BUFFER: 'booking-buffer'
}, {
	today: null,
	roomTypesData: {},
	translationIds: {}, // {%Original type ID%: [%Translated type IDs%]}
	dateRules: null,
	typeRules: {},
	seasons: {},
	/**
	 *
	 * @param {seasons: Object, rules: Object, roomTypesData: Object} data
	 */
	init: function( data ) {
		MPHB.HotelDataManager.myThis = this;
		this.setToday( $.datepick.parseDate( MPHB._data.settings.dateTransferFormat, data.today ) );
		this.initSeasons( data.seasons );
		this.initRoomTypesData( data.roomTypesData, data.rules );
		this.initRules( data.rules );
	},

	/**
	 *
	 * @returns {undefined}
	 */
	initRoomTypesData: function( roomTypesData, rules ) {
		var self = this;
		$.each( roomTypesData, function( id, data ) {
			id = parseInt( id );

			// Save translation IDs
			var originalId = parseInt( data.originalId );
			if ( originalId != id ) {
				if ( !self.translationIds.hasOwnProperty( originalId ) ) {
					self.translationIds[originalId] = [];
				}
				self.translationIds[originalId].push( id );
			}

			var roomTypeData = new MPHB.RoomTypeData( id, data );

			// Block all rooms with global rules (where "Accommodation Type" = "All"
			// and "Accommodation" = "All")
			$.each( rules.dates, function ( dateFormatted, restrictions ) {
				if ( restrictions['not_stay_in'] ) {
					roomTypeData.blockAllRoomsOnDate( dateFormatted );
				}
			} );

			// Block all rooms with custom rules, where "Accommodation Type" = originalId
			// and "Accommodation" = "All"
			if ( rules.blockedTypes.hasOwnProperty( originalId ) ) {
				$.each( rules.blockedTypes[originalId], function ( dateFormatted, restrictions ) {
					if ( restrictions['not_stay_in'] ) {
						roomTypeData.blockAllRoomsOnDate( dateFormatted );
					}
				} );
			}

			self.roomTypesData[id] = roomTypeData;
		} );
	},
	initRules: function( rules ) {
		this.dateRules = new MPHB.DateRules( rules.dates );

		var self = this;
		$.each( rules.blockedTypes, function( id, dates ) {
			self.typeRules[id] = new MPHB.DateRules( dates );

			// Add the same date rules for each translation
			if ( self.translationIds.hasOwnProperty( id ) ) {
				$.each( self.translationIds[id], function( i, translationId ) {
					self.typeRules[translationId] = new MPHB.DateRules( dates );
				} );
			}
		} );

		this.reservationRules = new MPHB.ReservationRulesChecker(rules.reservationRules);
	},
	initSeasons: function(seasons){
		$.each(seasons, this.proxy(function(id, seasonData) {
			this.seasons[id] = new MPHB.Season(seasonData);
		}));
	},
	/**
	 *
	 * @param {Date} date
	 * @returns {undefined}
	 */
	setToday: function( date ) {
		this.today = date;
	},
	/**
	 *
	 * @param {int|string} id ID of roomType
	 * @returns {MPHB.RoomTypeData|false}
	 */
	getRoomTypeData: function( id ) {
		return this.roomTypesData.hasOwnProperty( id ) ? this.roomTypesData[id] : false;
	},

	/**
	 *
	 * @param {Object} dateData
	 * @param {Date} date
	 * @param {string} [type=""] checkIn, checkOut or empty string
	 * @param {Date} [dateForRules] date for find actual rules
	 * @returns {Object}
	 */
	fillDateCellData: function( dateData, date, type, dateForRules ) {
		if (!dateForRules) {
			dateForRules = date;
		}
		var rulesTitles = [ ];
		var rulesClasses = [ ];
		var roomTypeId = dateData.roomTypeId;

		if ( this.notStayIn( date, roomTypeId ) ) {
			rulesTitles.push( MPHB._data.translations.notStayIn );
			rulesClasses.push( 'mphb-not-stay-in-date' );
		}
		if (type == 'checkIn' && this.notCheckIn(date, roomTypeId, dateForRules)) {
			rulesTitles.push( MPHB._data.translations.notCheckIn );
			rulesClasses.push( 'mphb-not-check-in-date' );
		}
		if (type == 'checkOut' && this.notCheckOut( date, roomTypeId, dateForRules ) ) {
			rulesTitles.push( MPHB._data.translations.notCheckOut );
			rulesClasses.push( 'mphb-not-check-out-date' );
		}

		if( MPHB.Utils.compareDates(date, this.today, '>=') && this.isEarlierThanMinAdvanceDate( date, roomTypeId ) ) {
			rulesTitles.push( MPHB._data.translations.earlierMinAdvance );
		}

		if( type != 'checkOut' && this.isLaterThamMaxAdvanceDate( date, roomTypeId ) ) {
			rulesTitles.push( MPHB._data.translations.laterMaxAdvance );
		}

		if ( rulesTitles.length ) {
			dateData.title += ' ' + MPHB._data.translations.rules + ' ' + rulesTitles.join( ', ' );
		}

		if ( rulesClasses.length ) {
			dateData.dateClass += (dateData.dateClass.length ? ' ' : '') + rulesClasses.join( ' ' );
		}

		return dateData;
	},
	notStayIn: function( date, roomTypeId ) {
		var canStay = this.dateRules.canStayIn( date );

		if ( this.typeRules[roomTypeId] ) {
			canStay = canStay && this.typeRules[roomTypeId].canStayIn( date );
		}

		return !canStay;
	},
	/**
	 *
	 * @param {Date} date
	 * @param {number} roomTypeId
	 * @param {Date} [dateForRules]
	 * @return {boolean}
	 */
	notCheckIn: function( date, roomTypeId, dateForRules ) {
		// Now check in rules determines by check-in date, so dateForRules is unused by this moment
		if (!dateForRules) {
			dateForRules = date;
		}
		var canCheckIn = this.dateRules.canCheckIn( date );

		canCheckIn = canCheckIn && this.reservationRules.isCheckInSatisfy( date, roomTypeId );

		if ( this.typeRules[roomTypeId] ) {
			canCheckIn = canCheckIn && this.typeRules[roomTypeId].canCheckIn( date );
		}

		return !canCheckIn;
	},
	/**
	 *
	 * @param {Date} date
	 * @param {number}roomTypeId
	 * @param {Date} checkInDate
	 * @return {boolean}
	 */
	notCheckOut: function( date, roomTypeId, checkInDate ) {
		var canCheckOut = this.dateRules.canCheckOut( date );

		canCheckOut = canCheckOut && this.reservationRules.isCheckOutSatisfy( checkInDate, date, roomTypeId );

		if ( this.typeRules[roomTypeId] ) {
			canCheckOut = canCheckOut && this.typeRules[roomTypeId].canCheckOut( date );
		}

		return !canCheckOut;
	},

	/**
	 *
	 * @param {Date} date
	 * @returns {Boolean}
	 */
	isEarlierThanMinAdvanceDate: function( date, roomTypeId ) {
        var minCheckInDate = this.reservationRules.getMinCheckInDate( roomTypeId );

		return minCheckInDate != null
            && MPHB.Utils.compareDates( date, minCheckInDate, '<' );
	},

	/**
	 *
	 * @param {Date} date
	 * @returns {Boolean}
	 */
	isLaterThamMaxAdvanceDate: function( date, roomTypeId ) {
        var maxAdvanceDate = this.reservationRules.getMaxAdvanceDate( roomTypeId );

		return maxAdvanceDate != null
            && MPHB.Utils.compareDates( date, maxAdvanceDate, '>' );
	}

} );

MPHB.TermsSwitcher = can.Construct.extend(
	{},
	{
		/**
		 * @param {Object} element .mphb-checkout-terms-wrapper
		 */
		init: function( element, args ) {
			var terms = element.children( '.mphb-terms-and-conditions' );

			element.find( '.mphb-terms-and-conditions-link' ).on( 'click', function( event ){
				event.preventDefault();
				terms.toggleClass( 'mphb-active' );
			} );
		}
	}
);
MPHB.Utils = can.Construct.extend({
	/**
	 *
	 * @param {Date} date
	 * @returns {String}
	 */
	formatDateToCompare: function(date) {
		return $.datepick.formatDate('yyyymmdd', date);
	},
    /**
     * @param {Date} date1
     * @param {Date} date2
     * @param {String|Null} operator Optional.
     * @returns {Number|Boolean}
     *
     * @since 3.8.7
     */
    compareDates: function(date1, date2, operator) {
        var date1 = MPHB.Utils.formatDateToCompare(date1);
        var date2 = MPHB.Utils.formatDateToCompare(date2);

        if (operator != null) {
            switch (operator) {
                case '>' : return date1 >  date2; break;
                case '>=': return date1 >= date2; break;
                case '<' : return date1 <  date2; break;
                case '<=': return date1 <= date2; break;
                case '=' :
                case '==': return date1 == date2; break;
                case '!=': return date1 != date2; break;
                default:
                    return false;
                    break;
            }
        } else {
            if (date1 > date2) {
                return 1;
            } else if (date1 < date2) {
                return -1;
            } else {
                return 0;
            }
        }
    },
	/**
	 *
	 * @param {Date} date
	 * @returns {Date}
	 */
	cloneDate: function(date) {
		return new Date(date.getTime());
	},

	/**
	 *
	 * @param {Array} arr
	 * @returns {Array}
	 */
	arrayUnique: function(arr) {
		return arr.filter(function(value, index, self) {
			return self.indexOf(value) === index;
		});
	},

	/**
	 *
	 * @param {Array} arr
	 * @return {number}
	 */
	arrayMin: function(arr) {
		return Math.min.apply(null, arr);
	},

	/**
	 *
	 * @param {Array} arr
	 * @return {number}
	 */
	arrayMax: function(arr) {
		return Math.max.apply(null, arr);
	},

	/**
	 *
	 * @param {Array} a
	 * @param {Array} b
	 * @return {Array}
	 */
	arrayDiff: function(a, b) {
		return a.filter(function(i) {
			return b.indexOf(i) < 0;
		});
	},

	/**
	 *
	 * @param {mixed} value
	 * @param {Array} arr
	 * @return {boolean}
	 */
	inArray: function(value, arr) {
		return arr.indexOf(value) !== -1;
	}
}, {});
MPHB.Gateway = can.Construct.extend( {}, {
	amount: 0,
	paymentDescription: '',
	init: function( args ) {
		this.billingSection = args.billingSection;
		this.initSettings( args.settings );
	},
	initSettings: function( settings ) {
		this.amount = settings.amount;
		this.paymentDescription = settings.paymentDescription;
	},
    /**
     * @param {Number} amount The price to pay.
     * @param {Object} customer Maximum information about the customer. See
     *     MPHB.CheckoutForm.getCustomerDetails() for more details.
     * @returns {Promise}
     *
     * @since 3.6.0 added new parameter - amount.
     * @since 3.6.0 added new parameter - customer.
     * @since 3.6.0 changed the return value from Boolean to Promise.
     */
    canSubmit: function (amount, customer) {
        return Promise.resolve(true);
    },
	updateData: function( data ) {
		this.amount = data.amount;
		this.paymentDescription = data.paymentDescription;
	},
	afterSelection: function( newFieldset ) {},
	cancelSelection: function() {},
    /**
     * @param {String} name
     * @param {String} value
     *
     * @since 3.6.0
     */
    onInput: function (name, value) {}
} );
/**
 *
 * @requires ./gateway.js
 */
MPHB.BeanstreamGateway = MPHB.Gateway.extend( {}, {
	scriptUrl: '',
	isCanSubmit: false,
	loadHandler: null,
	validityHandler: null,
	tokenRequestHandler: null,
	tokenUpdatedHandler: null,
	initSettings: function( settings ) {
		this._super( settings );
		this.scriptUrl = settings.scriptUrl || 'https://payform.beanstream.com/v1.1.0/payfields/beanstream_payfields.js';
		this.validityHandler = this.validityChanged.bind(this);
		this.tokenRequestHandler = this.tokenRequested.bind(this);
		this.tokenUpdatedHandler = this.tokenUpdated.bind(this);
	},
    canSubmit: function (amount, customer) {
        return Promise.relosve(this.isCanSubmit);
    },
	afterSelection: function( newFieldset ) {
		this._super( newFieldset );

		if ( newFieldset.length > 0 ) {
			var script = document.createElement( 'script' );
			// <script> must have id "fields-script" or it will fail to init
			script.id = 'payfields-script';
			script.src = this.scriptUrl;
			script.dataset.submitform = 'true';
			// Use async load only. Otherwise the script will wait infinitely for window.load event
			script.dataset.async = 'true';

			// Create new handler for Beanstream "loaded" (inited) event
			if ( this.loadHandler != null ) {
				$(document).off( 'beanstream_payfields_loaded', this.loadHandler );
			}
			this.loadHandler = function( data ) {
				$( '[data-beanstream-id]' ).appendTo( newFieldset );
			};
			$(document).on( 'beanstream_payfields_loaded', this.loadHandler );

			newFieldset.append( script );
			newFieldset.removeClass( 'mphb-billing-fields-hidden' );
		}

		// See all available events: https://github.com/Beanstream/checkoutfields#payfields-
		$(document).on( 'beanstream_payfields_inputValidityChanged', this.validityHandler )
				   .on( 'beanstream_payfields_tokenRequested', this.tokenRequestHandler )
				   .on( 'beanstream_payfields_tokenUpdated', this.tokenUpdatedHandler );
	},
	cancelSelection: function() {
		$(document).off( 'beanstream_payfields_inputValidityChanged', this.validityHandler )
				   .off( 'beanstream_payfields_tokenRequested', this.tokenRequestHandler )
				   .off( 'beanstream_payfields_tokenUpdated', this.tokenUpdatedHandler );
	},
	validityChanged: function( event ) {
		var eventDetail = event.eventDetail || event.originalEvent.eventDetail;
		if ( !eventDetail.isValid ) {
			this.isCanSubmit = false;
		}
	},
	tokenRequested: function( event ) {
		this.billingSection.showPreloader();
	},
	tokenUpdated: function( event ) {
		var eventDetail = event.eventDetail || event.originalEvent.eventDetail;
		if ( eventDetail.success ) {
			this.isCanSubmit = true;
		} else {
			this.isCanSubmit = false;
			this.billingSection.showError( MPHB._data.translations.tokenizationFailure.replace( '(%s)', eventDetail.message ) );
		}
		this.billingSection.hidePreloader();
	}
} );
/**
 * @requires ./gateway.js
 */
MPHB.BillingSection = can.Control.extend( {}, {
	updateBillingFieldsTimeout: null,
	parentForm: null,
	billingFieldsWrapperEl: null,
	gateways: {},
    /** @since 3.6.1 */
    amounts: {},
	lastGatewayId: null,
	init: function( el, args ) {
		this.parentForm = args.form;
		this.billingFieldsWrapperEl = this.element.find( '.mphb-billing-fields' );
		this.initGateways( args.gateways );
	},
    initGateways: function (gateways) {
        var self = this;

        $.each(gateways, function(gatewayId, settings) {
            var gatewaySettings = {
                billingSection: self,
                settings: settings
            };

            var gateway = null;

            switch (gatewayId) {
                case 'braintree':
                    gateway = new MPHB.BraintreeGateway(gatewaySettings);
                    break;
                case 'beanstream':
                    gateway = new MPHB.BeanstreamGateway(gatewaySettings);
                    break;
                case 'stripe':
                    gateway = new MPHB.StripeGateway(gatewaySettings);
                    break;
                default:
                    gateway = new MPHB.Gateway(gatewaySettings);
                    break;
            }

            if (gateway != null) {
                self.gateways[gatewayId] = gateway;
                self.amounts[gatewayId] = settings.amount;
            }
        }); // For each gateway

        this.notifySelectedGateway();
    },
	'[name="mphb_gateway_id"] change': function( el, e ) {
		var self = this;
		var gatewayId = el.val();
		this.showPreloader();
		this.billingFieldsWrapperEl.empty().addClass( 'mphb-billing-fields-hidden' );
		clearTimeout( this.updateBillingFieldsTimeout );
		this.updateBillingFieldsTimeout = setTimeout( function() {
			var formData = self.parentForm.parseFormToJSON();
			$.ajax( {
				url: MPHB._data.ajaxUrl,
				type: 'GET',
				dataType: 'json',
				data: {
					action: 'mphb_get_billing_fields',
					mphb_nonce: MPHB._data.nonces.mphb_get_billing_fields,
					mphb_gateway_id: gatewayId,
					formValues: formData,
                    lang: MPHB._data.settings.currentLanguage
				},
				success: function( response ) {
					if ( response.hasOwnProperty( 'success' ) ) {
						if ( response.success ) {
							// Disable previous selected gateway
							if ( self.lastGatewayId ) {
								self.gateways[self.lastGatewayId].cancelSelection();
							}

							self.billingFieldsWrapperEl.html( response.data.fields );
							if ( response.data.hasVisibleFields ) {
								self.billingFieldsWrapperEl.removeClass( 'mphb-billing-fields-hidden' );
							} else {
								self.billingFieldsWrapperEl.addClass( 'mphb-billing-fields-hidden' );
							}

							self.notifySelectedGateway( gatewayId );

						} else {
							self.showError( response.data.message );
						}
					} else {
						self.showError( MPHB._data.translations.errorHasOccured );
					}
				},
				error: function( jqXHR ) {
					self.showError( MPHB._data.translations.errorHasOccured );
				},
				complete: function( jqXHR ) {
					self.hidePreloader();
				}
			} );
		}, 500 );
	},
	hideErrors: function() {
		this.parentForm.hideErrors();
	},
	showError: function( message ) {
		this.parentForm.showError( message );
	},
	showPreloader: function() {
		this.parentForm.showPreloader();
	},
	hidePreloader: function() {
		this.parentForm.hidePreloader();
	},
    /**
     * @param {String} name
     * @param {String} value
     *
     * @since 3.6.0
     */
    onInput: function (name, value) {
        var gateway = this.gateways[this.getSelectedGateway()];

        if (gateway) {
            gateway.onInput(name, value);
        }
    },
    /**
     * @param {Number} amount The price to pay.
     * @param {Object} customer Maximum information about the customer. See
     *     MPHB.CheckoutForm.getCustomerDetails() for more details.
     * @returns {Promise}
     *
     * @since 3.6.0 added new parameter - amount.
     * @since 3.6.0 added new parameter - customer.
     * @since 3.6.0 changed the return value from Boolean to Promise.
     */
    canSubmit: function (amount, customer) {
        var gateway = this.gateways[this.getSelectedGateway()];

        if (gateway) {
            return gateway.canSubmit(amount, customer);
        } else {
            return Promise.resolve(true);
        }
    },
    getSelectedGateway: function () {
        var gateways = this.element.find('[name="mphb_gateway_id"]');

        if (gateways.length == 1) {
            return gateways.val();
        } else {
            return gateways.filter(':checked').val();
        }
    },
    /** @since 3.6.1 */
    getSelectedGatewayAmount: function () {
        var gatewayId = this.getSelectedGateway();

        if (this.amounts.hasOwnProperty(gatewayId)) {
            return this.amounts[gatewayId];
        } else {
            return 0;
        }
    },
    notifySelectedGateway: function (gatewayId) {
        gatewayId = gatewayId || this.getSelectedGateway();

        if (gatewayId && this.gateways.hasOwnProperty(gatewayId)) {
            this.gateways[gatewayId].afterSelection(this.billingFieldsWrapperEl);

            // Set up updated value of the country
            var selectedCountry = this.parentForm.getCountry();

            if (selectedCountry !== false) {
                this.gateways[gatewayId].onInput('country', selectedCountry);
            }
        }

        this.lastGatewayId = gatewayId;
    },
	updateGatewaysData: function( gatewaysData ) {
		var self = this;
		$.each( gatewaysData, function( gatewayId, gatewayData ) {
			if ( self.gateways.hasOwnProperty( gatewayId ) ) {
				self.gateways[gatewayId].updateData( gatewayData );
			}
		} );
	}
} );
/**
 *
 * @requires ./gateway.js
 */
MPHB.BraintreeGateway = MPHB.Gateway.extend( {}, {
	clientToken: '',
	checkout: null, // Used to remove all fields and events of the Braintree SDK
	initSettings: function( settings ) {
		this._super( settings );
		this.clientToken = settings.clientToken;
	},
    canSubmit: function (amount, customer) {
        return Promise.resolve(this.isNonceStored());
    },
	/**
	 *
	 * @param {String} nonce
	 * @returns {undefined}
	 */
	storeNonce: function( nonce ) {
		var $nonceEl = this.billingSection.billingFieldsWrapperEl.find( '[name="mphb_braintree_payment_nonce"]' );
		$nonceEl.val( nonce );
	},
	/**
	 *
	 * @returns {Boolean}
	 */
	isNonceStored: function() {
		var $nonceEl = this.billingSection.billingFieldsWrapperEl.find( '[name="mphb_braintree_payment_nonce"]' );
		return $nonceEl.length && $nonceEl.val() != '';
	},
	afterSelection: function ( newFieldset ) {
		this._super( newFieldset );
		if ( braintree != undefined ) {
			var containerId = 'mphb-braintree-container-' + this.clientToken.substr(0, 8);
			newFieldset.append('<div id="' + containerId + '"></div>');

			var self = this;
			braintree.setup( this.clientToken, 'dropin', {
				container: containerId,
				onReady: function( integration ) {
					// We can use integration's teardown() method to remove all DOM elements and attached events
					self.checkout = integration;
				},
				onPaymentMethodReceived: function( response ) {
					self.storeNonce( response.nonce );
					self.billingSection.parentForm.element.submit();
					self.billingSection.showPreloader();
				}
			} );

			newFieldset.removeClass( 'mphb-billing-fields-hidden' );
		}
	},
	cancelSelection: function() {
		this._super();
		if ( this.checkout != null ) {
			var self = this;
			this.checkout.teardown( function() {
				self.checkout = null; // braintree.setup() can safely be run again
			} );
		}
	}
} );

MPHB.CouponSection = can.Control.extend( {}, {
	applyCouponTimeout: null,
	parentForm: null,
	appliedCouponEl: null,
	couponEl: null,
	messageHolderEl: null,
	init: function( el, args ) {
		this.parentForm = args.form;
		this.couponEl = el.find( '[name="mphb_coupon_code"]' );
		this.appliedCouponEl = el.find( '[name="mphb_applied_coupon_code"]' );
		this.messageHolderEl = el.find( '.mphb-coupon-message' );
	},
	'.mphb-apply-coupon-code-button click': function( el, e ) {
		e.preventDefault();
		e.stopPropagation();

		this.clearMessage();

		var couponCode = this.couponEl.val();
		if ( !couponCode.length ) {
			this.showMessage( MPHB._data.translations.emptyCouponCode );
			return;
		}

		this.appliedCouponEl.val( '' );

		var self = this;
		this.showPreloader();

		clearTimeout( this.applyCouponTimeout );
		this.applyCouponTimeout = setTimeout( function() {
			var formData = self.parentForm.parseFormToJSON();
			$.ajax( {
				url: MPHB._data.ajaxUrl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'mphb_apply_coupon',
					mphb_nonce: MPHB._data.nonces.mphb_apply_coupon,
					mphb_coupon_code: couponCode,
					formValues: formData,
                    lang: MPHB._data.settings.currentLanguage
				},
				success: function( response ) {
					if ( response.hasOwnProperty( 'success' ) ) {
						if ( response.success ) {

							self.parentForm.setCheckoutData( response.data );

							self.couponEl.val( '' );
							self.appliedCouponEl.val( response.data.coupon.applied_code );
							self.showMessage( response.data.coupon.message );

						} else {
							self.showMessage( response.data.message );
						}
					} else {
						self.showMessage( MPHB._data.translations.errorHasOccured );
					}
				},
				error: function( jqXHR ) {
					self.showMessage( MPHB._data.translations.errorHasOccured );
				},
				complete: function( jqXHR ) {
					self.hidePreloader();
				}
			} );
		}, 500 );
	},
	removeCoupon: function() {
		this.appliedCouponEl.val( '' );
		this.clearMessage();
	},
	showPreloader: function() {
		this.parentForm.showPreloader();
	},
	hidePreloader: function() {
		this.parentForm.hidePreloader();
	},
	clearMessage: function() {
		this.messageHolderEl.html( '' ).addClass( 'mphb-hide' );
	},
	showMessage: function( message ) {
		this.messageHolderEl.html( message ).removeClass( 'mphb-hide' );
	}
} );
/**
 * @requires ./billing-section.js
 * @requires ./coupon-section.js
 * @required ./guests-chooser.js
 */
MPHB.CheckoutForm = can.Control.extend( {
	myThis: null
}, {
	priceBreakdownTableEl: null,
	bookBtnEl: null,
	errorsWrapperEl: null,
	preloaderEl: null,
	billingSection: null,
	couponSection: null,
	waitResponse: false,
	updateInfoTimeout: null,
	updateRatesTimeout: null,
	freeBooking: false,
    currentInfoAjax: null,
    /** @since 3.6.0 */
    toPay: 0,
	init: function( el, args ) {
		MPHB.CheckoutForm.myThis = this;
		this.bookBtnEl = this.element.find( 'input[type=submit]' );
		this.errorsWrapperEl = this.element.find( '.mphb-errors-wrapper' );
		this.preloaderEl = this.element.find( '.mphb-preloader' );
		this.priceBreakdownTableEl = this.element.find( 'table.mphb-price-breakdown' );
		if ( MPHB._data.settings.useBilling ) {
			this.billingSection = new MPHB.BillingSection( this.element.find( '#mphb-billing-details' ), {
				'form': this,
				'gateways': MPHB._data.gateways
			} );
		}
		if ( MPHB._data.settings.useCoupons ) {
			this.couponSection = new MPHB.CouponSection( this.element.find( '#mphb-coupon-details' ), {
				'form': this,
			} );
		}
        this.element.find('.mphb-room-details').each(function (i, element) {
            new MPHB.GuestsChooser(
                $(element),
                {
                    minAdults: MPHB._data.checkout.min_adults,
                    minChildren: MPHB._data.checkout.min_children
                }
            );
        });
	},
    /**
     * @param {Number} amount
     * @param {String} priceHtml
     *
     * @since 3.6.0 removed the "value" parameter.
     * @since 3.6.0 added new parameter - amount.
     * @since 3.6.0 added new parameter - priceHtml.
     */
    setTotal: function (amount, priceHtml) {
        this.toPay = amount;
        this.element.find('.mphb-total-price-field').html(priceHtml);
    },
    /**
     * @param {Number} amount
     * @param {String} priceHtml
     *
     * @since 3.6.0 removed the "value" parameter.
     * @since 3.6.0 added new parameter - amount.
     * @since 3.6.0 added new parameter - priceHtml.
     */
    setDeposit: function (amount, priceHtml) {
        this.toPay = amount;
        this.element.find('.mphb-deposit-amount-field').html(priceHtml);
    },
	setupPriceBreakdown: function( priceBreakdown ) {
		this.priceBreakdownTableEl.replaceWith( priceBreakdown );
		this.priceBreakdownTableEl = this.element.find( 'table.mphb-price-breakdown' );
	},
	updateCheckoutInfo: function() {
		var self = this;
		self.hideErrors();
		self.showPreloader();
		clearTimeout( this.updateInfoTimeout );
		this.updateInfoTimeout = setTimeout( function() {
			var data = self.parseFormToJSON();
			self.currentInfoAjax = $.ajax( {
				url: MPHB._data.ajaxUrl,
				type: 'GET',
				dataType: 'json',
				data: {
					action: 'mphb_update_checkout_info',
					mphb_nonce: MPHB._data.nonces.mphb_update_checkout_info,
					formValues: data,
                    lang: MPHB._data.settings.currentLanguage
				},
                beforeSend: function () {
                    if (self.currentInfoAjax != null) {
                        self.currentInfoAjax.abort();
                        self.hideErrors();
                    }
                },
				success: function( response ) {
					if ( response.hasOwnProperty( 'success' ) ) {
						if ( response.success ) {
							self.setCheckoutData( response.data );
						} else {
							self.showError( response.data.message );
						}
					} else {
						self.showError( MPHB._data.translations.errorHasOccured );
					}
				},
				error: function( jqXHR ) {
					self.showError( MPHB._data.translations.errorHasOccured );
				},
				complete: function( jqXHR ) {
					self.hidePreloader();
                    self.currentInfoAjax = null;
				}
			} );
		}, 500 );
	},
    setCheckoutData: function (data) {
        this.setTotal(data.newAmount, data.priceHtml);
        this.setupPriceBreakdown(data.priceBreakdown);

        if (MPHB._data.settings.useBilling) {
            this.setDeposit(data.depositAmount, data.depositPrice);
            this.billingSection.updateGatewaysData(data.gateways);

            if (data.isFree) {
                this.setFreeMode();
            } else {
                this.unsetFreeMode();
            }
        }
    },
	setFreeMode: function() {
		this.freeBooking = true;
		this.billingSection.element.addClass( 'mphb-hide' );
		this.element.append( $( '<input />', {
			'type': 'hidden',
			'name': 'mphb_gateway_id',
			'value': 'manual',
			'id': 'mphb-manual-payment-input'
		} ) );
	},
	unsetFreeMode: function() {
		this.freeBooking = false;
		this.billingSection.element.removeClass( 'mphb-hide' );
		this.element.find( '#mphb-manual-payment-input' ).remove();
	},
	updateRatePrices: function( room ) {
		if ( !room || !room.length ) {
			return;
		}

		var index = parseInt( room.attr( 'data-index' ) );

		// Get IDs of all rates for this room
		var rates = room.find( '.mphb_sc_checkout-rate' );
		var rateIds = $.map( rates, function( rate ) {
			return parseInt( rate.value );
		} );

		if ( rateIds.length <= 1 ) {
			// Single rate does not show, nothing to update
			return;
		}

		var formData = this.parseFormToJSON();
		var details = formData['mphb_room_details'][index];
		var adults = details.adults || '';
		var children = details.children || '';

		clearTimeout( this.updateRatesTimeout );
		this.updateRatesTimeout = setTimeout( function() {
			$.ajax( {
				url: MPHB._data.ajaxUrl,
				type: 'GET',
				dataType: 'json',
				data: {
					action: 'mphb_update_rate_prices',
					mphb_nonce: MPHB._data.nonces.mphb_update_rate_prices,
					rates: rateIds,
					adults: adults,
					children: children,
					check_in_date: formData['mphb_check_in_date'],
					check_out_date: formData['mphb_check_out_date'],
                    lang: MPHB._data.settings.currentLanguage
				},
				success: function( response ) {
					if ( !response.hasOwnProperty( 'success' ) ) {
						return;
					}

					var prices = response.data; // {%Rate ID%: %Price HTML%}
					$.each( rates, function( i, rate ) {
						var rateId = rate.value;

						if ( prices[rateId] == undefined ) {
							return;
						}

						var parent = $( rate ).parent().children( 'strong' );
						// Remove old price
						parent.children( '.mphb-price' ).remove();
						// Add new price
						parent.append( prices[rateId] );
					} );
				}
			} );
		}, 500 );
	},
	'.mphb_checkout-guests-chooser change': function( el, e ) {
		this.updateRatePrices( el.closest( '.mphb-room-details' ) );
		this.updateCheckoutInfo();
	},
	'.mphb_checkout-rate change': function( el, e ) {
		this.updateCheckoutInfo();
	},
	'.mphb_checkout-service, .mphb_checkout-service-adults change': function( el, e ) {
		this.updateCheckoutInfo();
	},
	'.mphb_checkout-service-quantity input': function( el, e ) {
		this.updateCheckoutInfo();
	},
    /**
     * @param {Object} element
     * @param {Object} event
     *
     * @since 3.6.0
     */
    'select[name="mphb_country"] change': function (element, event) {
        if (this.billingSection != null) {
            var country = $(element).val();
            this.billingSection.onInput('country', country);
        }
    },
    /**
     * @returns {String|Boolean} Country name or FALSE.
     *
     * @since 3.6.0
     */
    getCountry: function () {
        return this.getCustomerDetail('country');
    },
	// See also assets/js/admin/dev/controls/price-breakdown-ctrl.js
	'.mphb-price-breakdown-expand click': function( el, e ) {
		e.preventDefault();
		$( el ).blur(); // Don't save a:focus style on last clicked item
		var tr = $( el ).parents( 'tr.mphb-price-breakdown-group' );
		tr.find( '.mphb-price-breakdown-rate' ).toggleClass( 'mphb-hide' );
		tr.nextUntil( 'tr.mphb-price-breakdown-group' ).toggleClass( 'mphb-hide' );
        $(el).children('.mphb-inner-icon').toggleClass('mphb-hide');
	},
	hideErrors: function() {
		this.errorsWrapperEl.empty().addClass( 'mphb-hide' );
	},
	showError: function( message ) {
		this.errorsWrapperEl.html( message ).removeClass( 'mphb-hide' );
	},
	showPreloader: function() {
		this.waitResponse = true;
		this.bookBtnEl.attr( 'disabled', 'disabled' );
		this.preloaderEl.removeClass( 'mphb-hide' );
	},
	hidePreloader: function() {
		this.waitResponse = false;
		this.bookBtnEl.removeAttr( 'disabled' );
		this.preloaderEl.addClass( 'mphb-hide' );
	},
	parseFormToJSON: function() {
		return this.element.serializeJSON();
	},
    /**
     * @param {String} fieldName
     * @returns {Object}
     *
     * @since 3.7.2
     */
    getCustomerDetail: function (fieldName) {
        var fieldElement = this.element.find('#mphb_' + fieldName);

        if (fieldElement.length > 0) {
            return fieldElement.val();
        } else {
            return false;
        }
    },
    /**
     * @returns {Object} The maximum information about the customer: name, email,
     *     full address (if required) etc.
     *
     * @since 3.6.0
     */
    getCustomerDetails: function () {
        var customer = {
            email: '',
            first_name: '',
            last_name: ''
        };

        var customerFields = ['name', 'first_name', 'last_name', 'email', 'phone',
            'country', 'address1', 'city', 'state', 'zip'];
        var self = this;

        customerFields.forEach(function (fieldName) {
            var customerDetail = self.getCustomerDetail(fieldName);

            if (customerDetail !== false) {
                customer[fieldName] = customerDetail;
            }
        });

        if (!customer.name) {
            var name = customer.first_name + ' ' + customer.last_name;
            customer.name = name.trim();
        }

        return customer;
    },
    /**
     * @since 3.6.1
     */
    getToPayAmount: function () {
        var toPay = this.toPay;

        if (toPay == 0) {
            toPay = this.billingSection.getSelectedGatewayAmount();
        }

        return toPay;
    },
    /**
     * @since 3.6.0 added support of promises.
     */
    'submit': function( el, e ) {
        if (this.waitResponse) {
            return false;
        } else if (MPHB._data.settings.useBilling && !this.freeBooking) {
            var amount = this.getToPayAmount();
            var customer = this.getCustomerDetails();
            var self = this;

            this.showPreloader();

            this.billingSection.canSubmit(amount, customer)
                .then(function (canSubmit) {
                    if (canSubmit) {
                        // jQuery.submit() will re-trigger the "submit" event
                        // (and current function). Instead, method form.submit()
                        // does not trigger the event
                        self.element[0].submit();
                    } else {
                        self.hidePreloader();
                    }
                })
                .catch(function (error) {
                    self.hidePreloader();
                    console.error('Billing error. ' + error.message);
                });

            // Wait for response from billing section
            return false;
        }
    },
	'#mphb-price-details .mphb-remove-coupon click': function( el, e ) {
		e.preventDefault();
		e.stopPropagation();

		if ( MPHB._data.settings.useCoupons ) {
			this.couponSection.removeCoupon();
			this.updateCheckoutInfo();
		}
	}
} );
/**
 * @since 3.7.2
 */
MPHB.GuestsChooser = can.Control.extend(
    {},
    {
        $adultsChooser: null,
        $childrenChooser: null,

        minAdults: 0,
        minChildren: 0,

        maxAdults: 0,
        maxChildren: 0,

        totalCapacity: 0,

        init: function (element, args) {
            var $selects = element.find('.mphb_checkout-guests-chooser');

            if ($selects.length < 2) {
                return;
            }

            this.$adultsChooser = $($selects[0]);
            this.$childrenChooser = $($selects[1]);

            this.minAdults = args.minAdults;
            this.minChildren = args.minChildren;

            this.maxAdults = parseInt(this.$adultsChooser.data('max-allowed'));
            this.maxChildren = parseInt(this.$childrenChooser.data('max-allowed'));

            this.totalCapacity = parseInt($selects.data('max-total'));

            if (this.maxAdults + this.maxChildren > this.totalCapacity) {
                this.$adultsChooser.on('change', this.limitChildren.bind(this));
            }
        },

        limitChildren: function () {
            var adults = this.$adultsChooser.val();
            var maxChildren = this.findMax(adults, this.minChildren, this.maxChildren);

            this.limitOptions(this.$childrenChooser, this.minChildren, maxChildren, adults);
        },

        findMax: function (oppositeValue, defaultMin, defaultMax) {
            var maxValue = this.totalCapacity;

            if (oppositeValue !== '') {
                maxValue = this.totalCapacity - oppositeValue;

                // Don't make less than min possible number of adults/children
                maxValue = Math.max(defaultMin, maxValue);
            }

            // Don't make bigger than max possible number of adults/children
            return Math.min(maxValue, defaultMax);
        },

        limitOptions: function ($select, min, max, oppositeValue) {
            var maxValue = min;

            // Remove all options bigger than %max%
            $select.children().each(function (i, element) {
                var value = element.value;

                if (value !== '') {
                    value = parseInt(value);

                    if (value > max) {
                        $(element).remove();
                    } else if (value > maxValue) {
                        maxValue = value;
                    }
                }
            });

            // Fill options up to %max%
            for (var i = maxValue + 1; i <= max; i++) {
                var $option = jQuery('<option value="' + i + '">' + i + '</option>');
                $select.append($option);
            }

            // Reset selection (select "— Select —")
            if (oppositeValue !== '') {
                $select.children(':selected').prop('selected', false);
            }
        }
    }
);

/**
 * @requires ./gateway.js
 *
 * @since 3.6.0
 */
MPHB.StripeGateway = MPHB.Gateway.extend(
    {},
    {
        // Settings
        publicKey: '',
        locale: 'auto',
        currency: 'EUR',
        successUrl: window.location.href,
        defaultCountry: '',
        paymentDescription: 'Accommodation(s) reservation',
        statementDescriptor: 'Hotel Booking',
        fullAddressRequired: false,

        i18n: {},
        style: {},

        // API controls
        api: null,
        elements: null,
        cardControl: null,
        idealControl: null,
        ibanControl: null,

        // Own controls
        payments: null,
        customer: null, // See canSubmit() and setCustomer()

        /**
         * What we know about the customer at the start of the page. Generally
         * it's an empty object (on Checkout Page). But on Payment Request
         * Checkout page, when we already have the bookign and customer
         * information, this object is set with some basic information required
         * for the script.
         *
         * @see MPHB.StripeGateway.setCustomer()
         */
        defaultCustomer: null,

        // Elements
        mountWrapper: null,
        errorsWrapper: null,

        // Errors
        hasErrors: false,
        undefinedError: MPHB._data.translations.errorHasOccured,

        init: function (args) {
            this._super(args); // initSettings()

            // Docs: https://stripe.com/docs/stripe-js/reference#stripe-elements
            this.api          = Stripe(this.publicKey);
            this.elements     = this.api.elements({locale: this.locale});
            this.cardControl  = this.elements.create('card', {style: this.style, hidePostalCode: this.fullAddressRequired});
            this.idealControl = this.elements.create('idealBank', {style: this.style});
            this.ibanControl  = this.elements.create('iban', {style: this.style, supportedCountries: ['SEPA']});

            this.payments = new MPHB.StripeGateway.PaymentMethods(args.settings.paymentMethods, this.defaultCountry);

            this.addListeners();
        },

        initSettings: function (settings) {
            this._super(settings);

            this.publicKey           = settings.publicKey;
            this.locale              = settings.locale;
            this.currency            = settings.currency;
            this.successUrl          = settings.successUrl;
            this.defaultCountry      = settings.defaultCountry;
            this.paymentDescription  = settings.paymentDescription;
            this.statementDescriptor = settings.statementDescriptor;
            this.fullAddressRequired = MPHB._data.settings.fullAddressRequired;

            // See StripeGateway::getCheckoutData()
            this.defaultCustomer = settings.customer;

            this.i18n  = settings.i18n;
            this.style = settings.style;
        },

        addListeners: function () {
            var onChange = this.onChange.bind(this);

            this.cardControl.on('change', onChange);
            this.ibanControl.on('change', onChange);
        },

        onChange: function (event) {
            if (event.error) {
                this.showError(event.error.message);
                this.hasErrors = true;
            } else {
                this.hideErrors();
                this.hasErrors = false;
            }
        },

        onInput: function (name, value) {
            if (name == 'country') {
                this.payments.selectCountry(value);
            }
        },

        afterSelection: function (mountWrapper) {
            this._super(mountWrapper);

            mountWrapper.append(this.mountHtml());

            this.mountWrapper = mountWrapper;
            this.errorsWrapper = mountWrapper.find('#mphb-stripe-errors');

            // Mount all controls
            this.cardControl.mount('#mphb-stripe-card-element');

            if (this.payments.isEnabled('ideal')) {
                this.idealControl.mount('#mphb-stripe-ideal-element');
            }

            if (this.payments.isEnabled('sepa_debit')) {
                this.ibanControl.mount('#mphb-stripe-iban-element');
            }

            // Mount payments control
            this.payments.mount(mountWrapper);

            var self = this;
            this.payments.inputs.on('change', function () {
                // Clear previous control
                switch (self.payments.currentPayment) {
                    case 'card': self.cardControl.clear(); break;
                    case 'ideal': self.idealControl.clear(); break;
                    case 'sepa_debit': self.ibanControl.clear(); break;
                }

                // Select new control
                self.payments.selectPayment(this.value);
            });

            // Unhide elements
            mountWrapper.removeClass('mphb-billing-fields-hidden');
        },

        cancelSelection: function () {
            this._super();

            this.mountWrapper = null;
            this.errorsWrapper = null;

            // Unmount all controls
            this.cardControl.unmount();

            if (this.payments.isEnabled('ideal')) {
                this.idealControl.unmount();
            }

            if (this.payments.isEnabled('sepa_debit')) {
                this.ibanControl.unmount();
            }

            // Unmount payments control
            this.payments.unmount();
        },

        canSubmit: function (amount, customer) {
            if (this.hasErrors) {
                return Promise.resolve(false);
            }

            this.setCustomer(customer);

            if (this.payments.currentPayment == 'card') {
                return this.createPaymentIntent(amount)
                    .then(this.createCardPayment.bind(this))
                    .then(this.handleStripeErrors.bind(this))
                    .then(this.completeCardPayment.bind(this));
            } else {
                return this.createSource(amount)
                    .then(this.handleStripeErrors.bind(this))
                    .then(this.completeSourcePayment.bind(this));
            }
        },

        setCustomer: function (customerData) {
            var customer = $.extend({}, customerData); // Clone object

            // Init default fields (use data from StripeGateway::getCheckoutData())
            if (!customer.email) {
                customer.email = this.defaultCustomer.email;
            }

            if (!customer.name) {
                customer.name = this.defaultCustomer.name;
                customer.first_name = this.defaultCustomer.first_name;
                customer.last_name = this.defaultCustomer.last_name;
            }

            // Add field "country" if not exists
            if (!customer.hasOwnProperty('country')) {
                customer.country = this.payments.currentCountry;
            }

            this.customer = customer;
        },

        createPaymentIntent: function (amount) {
            var self = this;

            return new Promise(function (resolve, reject) {

                MPHB.post(
                    'create_stripe_payment_intent',
                    {
                        amount: amount,
                        description: self.paymentDescription
                    },
                    {
                        success: function (response) {
                            if (response.hasOwnProperty('success')) {
                                if (response.success) {
                                    var paymentIntent = {
                                        id:            response.data.id,
                                        client_secret: response.data.client_secret,
                                        object:        'payment_intent'
                                    };

                                    resolve(paymentIntent);
                                } else {
                                    self.showError(response.data.message);
                                    reject(new Error(response.data.message));
                                }
                            } else {
                                self.showError(self.undefinedError);
                                reject(new Error(self.undefinedError));
                            }
                        },
                        error: function (jqXHR) {
                            self.showError(self.undefinedError);
                            reject(new Error(self.undefinedError));
                        }
                    }
                ); // MPHB.post()

            }); // return new Promise()
        },

        createCardPayment: function (paymentIntent) {
            return this.api.handleCardPayment( // Another promise
                paymentIntent.client_secret,
                this.cardControl,
                {
                    payment_method_data: {
                        billing_details: {
                            name: this.customer.name,
                            email: this.customer.email
                        }
                    }
                }
            );
        },

        /**
         * @param {Number} amount
         * @returns {Promise}
         */
        createSource: function (amount) {
            var payment = this.payments.currentPayment;
            var customer = this.customer;

            // You can't even test "sepa_debit" until you get an invitation. See
            // more at https://stackoverflow.com/q/42583372/3918377

            var sourceArgs = {
                type: payment,
                amount: this.convertToSmallestUnit(amount),
                currency: this.currency.toLowerCase(),
                owner: {
                    name: customer.name,
                    email: customer.email
                },
                mandate: {
                    notification_method: 'none'
                },
                redirect: {
                    return_url: this.successUrl
                },
                statement_descriptor: this.statementDescriptor
            };

            var stripeControl = null;

            switch (payment) { // All except "card"
                case 'bancontact':
                    // Supported locales: https://stripe.com/docs/sources/bancontact#create-source
                    if (['en', 'de', 'fr', 'nl'].indexOf(this.locale) >= 0) {
                        sourceArgs.bancontact = {
                            preferred_language: this.locale
                        };
                    }
                    break;

                case 'ideal':
                    stripeControl = this.idealControl;
                    break;

                case 'giropay':
                    break;

                case 'sepa_debit':
                    stripeControl = this.ibanControl;
                    break;

                case 'sofort':
                    sourceArgs.sofort = {country: customer.country};

                    // Supported locales: https://stripe.com/docs/sources/sofort#create-source
                    if (['de', 'en', 'es', 'it', 'fr', 'nl', 'pl'].indexOf(this.locale) >= 0) {
                        sourceArgs.sofort.preferred_language = this.locale;
                    }
                    break;
            };

            if (stripeControl != null) {
                return this.api.createSource(stripeControl, sourceArgs);
            } else {
                return this.api.createSource(sourceArgs);
            }
        },

        handleStripeErrors: function (stripeResponse) {
            if (stripeResponse.error) {
                this.showError(stripeResponse.error.message);
                throw new Error(stripeResponse.error.message);
            } else if (stripeResponse.paymentIntent != null) {
                return stripeResponse.paymentIntent;
            } else {
                return stripeResponse.source;
            }
        },

        completeCardPayment: function (paymentIntent) {
            if (paymentIntent.status == 'succeeded' || paymentIntent.status == 'processing') {
                this.saveToCheckout('payment_method', this.payments.currentPayment);
                this.saveToCheckout('payment_intent_id', paymentIntent.id);

                return true; // Can submit

            } else {
                this.showError(this.undefinedError);
                throw new Error(this.undefinedError);
            }
        },

        completeSourcePayment: function (source) {
            this.saveToCheckout('payment_method', this.payments.currentPayment);
            this.saveToCheckout('source_id', source.id);
            this.saveToCheckout('redirect_url', source.redirect.url);

            return true; // Can submit
        },

        saveToCheckout: function (field, value) {
            this.mountWrapper.find('#mphb_stripe_' + field).val(value);
        },

        convertToSmallestUnit: function (amount) {
            // See all currencies presented as links on page
            // https://stripe.com/docs/currencies#presentment-currencies
            switch (this.currency) {
                // Zero decimal currencies
                case 'BIF':
                case 'CLP':
                case 'DJF':
                case 'GNF':
                case 'JPY':
                case 'KMF':
                case 'KRW':
                case 'MGA':
                case 'PYG':
                case 'RWF':
                case 'UGX':
                case 'VND':
                case 'VUV':
                case 'XAF':
                case 'XOF':
                case 'XPF':
                    amount = Math.floor(amount);
                    break;
                default:
                    amount = Math.round(amount * 100); // In cents
                    break;
            }

            return amount;
        },

        mountHtml: function () {
            var html = '<section id="mphb-stripe-payment-container" class="mphb-stripe-payment-container">';
                html += this.methodsHtml();

                html += this.fieldsHtml('card');
                html += this.fieldsHtml('bancontact');
                html += this.fieldsHtml('ideal');
                html += this.fieldsHtml('giropay');
                html += this.fieldsHtml('sepa_debit');
                html += this.fieldsHtml('sofort');

                html += '<div id="mphb-stripe-errors"></div>';
            html += '</section>';

            return html;
        },

        methodsHtml: function () {
            if (this.payments.onlyCardEnabled()) {
                return '';
            }

            var i18n = this.i18n;

            var html = '<nav id="mphb-stripe-payment-methods">';
                html += '<ul>';
                    this.payments.forEach(function (payment, paymentMethod, stripePayments) {
                        if (!paymentMethod.isEnabled) {
                            return; // Don't show disabled methods
                        }

                        var isSelected = stripePayments.isSelected(payment);

                        var activeClass = isSelected ? ' active' : '';
                        var checkedAttr = isSelected ? ' checked="checked"' : '';

                        html += '<li class="mphb-stripe-payment-method ' + payment + activeClass + '">';
                            html += '<label>';
                                html += '<input type="radio" name="stripe_payment_method" value="' + payment + '"' + checkedAttr + ' />' + i18n[payment];
                            html += '</label>';
                        html += '</li>';
                    });
                html += '</ul>';
            html += '</nav>';

            return html;
        },

        fieldsHtml: function (payment) {
            if (!this.payments.isEnabled(payment)) {
                return '';
            }

            var html = '';
            var hideClass = this.payments.isSelected(payment) ? '' : ' mphb-hide';

            html += '<div class="mphb-stripe-payment-fields ' + payment + hideClass + '">';
                html += '<fieldset>';
                    switch (payment) {
                        case 'card':
                            html += this.cardHtml(); break;
                        case 'ideal':
                            html += this.idealHtml(); break;
                        case 'sepa_debit':
                            html += this.ibanHtml(); break;
                        default:
                            html += this.redirectHtml(); break;
                    }
                html += '</fieldset>';

                if (payment == 'sepa_debit') {
                    html += '<p class="notice">' + this.i18n.iban_policy + '</p>';
                }
            html += '</div>';

            return html;
        },

        cardHtml: function () {
            var html = '';

            if (this.payments.onlyCardEnabled()) {
                html += '<label for="mphb-stripe-card-element">' + this.i18n.card_description + '</label>';
            }

            html += '<div id="mphb-stripe-card-element" class="mphb-stripe-element"></div>';

            return html;
        },

        idealHtml: function () {
            return '<label for="mphb-stripe-ideal-element">' + this.i18n.ideal_bank + '</label>'
                + '<div id="mphb-stripe-ideal-element" class="mphb-stripe-element"></div>';
        },

        ibanHtml: function () {
            return '<label for="mphb-stripe-iban-element">' + this.i18n.iban + '</label>'
                + '<div id="mphb-stripe-iban-element" class="mphb-stripe-element"></div>';
        },

        redirectHtml: function () {
            return '<p class="notice">' + this.i18n.redirect_notice + '</p>';
        },

        showError: function (message) {
            this.errorsWrapper.html(message).removeClass('mphb-hide');
        },

        hideErrors: function () {
            this.errorsWrapper.addClass('mphb-hide').text('');
        }
    }
);

MPHB.DirectBooking = can.Control.extend(
	{},
	{
		reservationForm: null, // form.mphb-booking-form
		elementsToHide: null, // Quantity wrappers, price block and reservation section
		quantitySection: null, // div.mphb-reserve-room-section
		wrapperWithSelect: null, // .mphb-rooms-quantity-wrapper.mphb-rooms-quantity-multiple
		wrapperWithoutSelect: null, // .mphb-rooms-quantity-wrapper.mphb-rooms-quantity-single
        priceWrapper: null, // .mphb-period-price
		quantitySelect: null, // select.mphb-rooms-quantity
		availableLabel: null, // span.mphb-available-rooms-count
		typeId: 0,
		init: function( el, args ) {
			this.reservationForm = args.reservationForm;

			this.elementsToHide = el.find( '.mphb-reserve-room-section, .mphb-rooms-quantity-wrapper, .mphb-regular-price' );

			this.quantitySection = el.find( '.mphb-reserve-room-section' );
			this.wrapperWithSelect = this.quantitySection.find( '.mphb-rooms-quantity-wrapper.mphb-rooms-quantity-multiple' );
			this.wrapperWithoutSelect = this.quantitySection.find( '.mphb-rooms-quantity-wrapper.mphb-rooms-quantity-single' );
            this.priceWrapper = this.quantitySection.find( '.mphb-period-price' );

			this.quantitySelect = this.quantitySection.find( '.mphb-rooms-quantity' );
			this.availableLabel = this.quantitySection.find( '.mphb-available-rooms-count' );

			this.typeId = el.find('input[name="mphb_room_type_id"]').val();
			this.typeId = parseInt( this.typeId );
		},
		hideSections: function() {
			this.elementsToHide.addClass( 'mphb-hide' );
		},
		showSections: function( showPrice ) {
			this.quantitySection.removeClass( 'mphb-hide' );

            if ( showPrice ) {
                this.priceWrapper.removeClass( 'mphb-hide' );
            }
		},
		resetQuantityOptions: function( count ) {
			this.quantitySelect.empty();

			for ( var i = 1; i <= count; i++ ) {
				var option = '<option value="' + i + '">' + i + '</option>';
				this.quantitySelect.append( option );
			}

			this.quantitySelect.val( 1 ); // Otherwise the last option will be active

			// Also update text "of %d accommodation(-s) available."
			this.availableLabel.text( count );

			if ( count > 1 ) {
				this.wrapperWithSelect.removeClass( 'mphb-hide' );
			} else {
				this.wrapperWithoutSelect.removeClass( 'mphb-hide' );
			}
		},
        setupPrice: function( price, priceHtml ) {
            this.priceWrapper.children( '.mphb-price, .mphb-price-period' ).remove();

            if ( price > 0 && priceHtml != '' ) {
                this.priceWrapper.append( priceHtml );
            }
        },
		showError: function( errorMessage ) {
			this.hideSections();
			this.reservationForm.showError( errorMessage );
		},
		/**
		 * See also MPHB.ReservationForm.onDatepickChange().
		 */
		"input.mphb-datepick change": function( el, e ) {
			this.hideSections();
		},
		".mphb-reserve-btn click": function( el, e ) {
			e.preventDefault();
			e.stopPropagation();

			this.reservationForm.clearErrors();
			this.reservationForm.setFormWaitingMode();

			var checkIn = this.reservationForm.checkInDatepicker.getDate();
			var checkOut = this.reservationForm.checkOutDatepicker.getDate();

			if ( !checkIn || !checkOut ) {
				if ( !checkIn ) {
					this.showError( MPHB._data.translations.checkInNotValid );
				} else {
					this.showError( MPHB._data.translations.checkOutNotValid );
				}
				this.reservationForm.setFormNormalMode();
				return;
			}

			var self = this;
			$.ajax( {
				url: MPHB._data.ajaxUrl,
				type: 'GET',
				dataType: 'json',
				data: {
					action: 'mphb_get_free_accommodations_amount',
					mphb_nonce: MPHB._data.nonces.mphb_get_free_accommodations_amount,
					typeId: this.typeId,
					checkInDate: $.datepick.formatDate(MPHB._data.settings.dateTransferFormat, checkIn),
					checkOutDate: $.datepick.formatDate(MPHB._data.settings.dateTransferFormat, checkOut),
                    adults: this.reservationForm.getAdults(),
                    children: this.reservationForm.getChildren(),
                    lang: MPHB._data.settings.currentLanguage
				},
				success: function( response ) {
					if ( response.success ) {
						self.resetQuantityOptions( response.data.freeCount );
                        self.setupPrice( response.data.price, response.data.priceHtml );
						self.showSections( response.data.price > 0 );
					} else {
						self.showError( response.data.message );
					}
				},
				error: function( jqXHR ) {
					self.showError( MPHB._data.translations.errorHasOccured );
				},
				complete: function( jqXHR ) {
					self.reservationForm.setFormNormalMode();
				}
			} );
		}
	}
);

MPHB.ReservationForm = can.Control.extend( {
	MODE_SUBMIT: 'submit',
	MODE_NORMAL: 'normal',
	MODE_WAITING: 'waiting'
}, {
	/**
	 * @var jQuery
	 */
	formEl: null,
	/**
	 * @var MPHB.RoomTypeCheckInDatepicker
	 */
	checkInDatepicker: null,
	/**
	 * @var MPHB.RoomTypeCheckOutDatepicker
	 */
	checkOutDatepicker: null,
	/**
	 * @var jQuery
	 */
	reserveBtn: null,
	/**
	 * @var jQuery
	 */
	reserveBtnPreloader: null,
	/**
	 * @var jQuery
	 */
	errorsWrapper: null,
	/**
	 * @var {bool}
	 */
	isDirectBooking: false,
	/**
	 * @var {MPHB.DirectBooking|null}
	 */
	directBooking: null,
	/**
	 * @var String
	 */
	mode: null,
	/**
	 * @var int
	 */
	roomTypeId: null,
	/**
	 * @var int
	 */
	searchRoomTypeId: null,
	/**
	 * @var MPHB.RoomTypeData
	 */
	roomTypeData: null,
	setup: function( el, args ) {
		this._super( el, args );
		this.mode = MPHB.ReservationForm.MODE_NORMAL;
	},
	init: function( el, args ) {
		this.formEl = el;
		this.roomTypeId = parseInt( this.formEl.attr( 'id' ).replace( /^booking-form-/, '' ) );
		this.isDirectBooking = MPHB._data.settings.isDirectBooking == '1';
		this.roomTypeData = MPHB.HotelDataManager.myThis.getRoomTypeData( this.roomTypeId);
		this.originalRoomTypeId = this.roomTypeData.originalId;
		this.searchRoomTypeId = this.isDirectBooking ? this.originalRoomTypeId : 0;
		this.errorsWrapper = this.formEl.find( '.mphb-errors-wrapper' );
		this.initCheckInDatepicker();
		this.initCheckOutDatepicker();
		this.initReserveBtn();

		// Init direct booking
		if ( this.isDirectBooking ) {
			this.directBooking = new MPHB.DirectBooking( el, { "reservationForm": this } );
		}

		$( window ).on( 'mphb-update-date-room-type-' + this.roomTypeId, this.proxy(function() {
			this.refreshDatepickers();
		}) );

		// Enable reservation rules on check-out date
		if ( this.checkInDatepicker.getDate() ) {
			this.updateCheckOutLimitations();
		}
	},
    /**
     * @returns {Number|String}
     *
     * @since 3.8.3
     */
    getAdults: function() {
        var input = this.formEl.find('[name="mphb_adults"]');
        return input.length > 0 ? parseInt(input.val()) : '';
    },
    /**
     * @returns {Number|String}
     *
     * @since 3.8.3
     */
    getChildren: function() {
        var input = this.formEl.find('[name="mphb_children"]');
        return input.length > 0 ? parseInt(input.val()) : '';
    },
	proceedToCheckout: function() {
		this.mode = MPHB.ReservationForm.MODE_SUBMIT;
		this.unlock();
		this.formEl.submit();
	},
	showError: function( message ) {
		this.clearErrors();
		var errorMessage = $( '<p>', {
			'class': 'mphb-error',
			'html': message
		} );
		this.errorsWrapper.append( errorMessage ).removeClass( 'mphb-hide' );
	},
	clearErrors: function() {
		this.errorsWrapper.empty().addClass( 'mphb-hide' );
	},
	lock: function() {
		this.element.find( '[name]' ).attr( 'disabled', 'disabled' );
		this.reserveBtn.attr( 'disabled', 'disabled' ).addClass( 'mphb-disabled' );
		this.reserveBtnPreloader.removeClass( 'mphb-hide' );
	},
	unlock: function() {
		this.element.find( '[name]' ).removeAttr( 'disabled' );
		this.reserveBtn.removeAttr( 'disabled' ).removeClass( 'mphb-disabled' );
		this.reserveBtnPreloader.addClass( 'mphb-hide' );
	},
	setFormWaitingMode: function() {
		this.mode = MPHB.ReservationForm.MODE_WAITING;
		this.lock();
	},
	setFormNormalMode: function() {
		this.mode = MPHB.ReservationForm.MODE_NORMAL;
		this.unlock();
	},
	initCheckInDatepicker: function() {
		var checkInEl = this.formEl.find( 'input[type="text"][id^=mphb_check_in_date]' );
		this.checkInDatepicker = new MPHB.RoomTypeCheckInDatepicker( checkInEl, {
			'form': this,
			'roomTypeId': this.searchRoomTypeId
		} );
		this.updateCheckInLimitations();
	},
	initCheckOutDatepicker: function() {
		var checkOutEl = this.formEl.find( 'input[type="text"][id^=mphb_check_out_date]' );
		this.checkOutDatepicker = new MPHB.RoomTypeCheckOutDatepicker( checkOutEl, {
			'form': this,
			'roomTypeId': this.searchRoomTypeId
		} );

	},
	initReserveBtn: function() {
		this.reserveBtn = this.formEl.find( '.mphb-reserve-btn' );
		this.reserveBtnPreloader = this.formEl.find( '.mphb-preloader' );

		this.setFormNormalMode();
	},
	/**
	 *
	 * @param {bool} setDate
	 * @returns {undefined}
	 */
	updateCheckInLimitations: function( setDate ) {
		if ( typeof setDate === 'undefined' ) {
			setDate = true;
		}
		var limitations = this.retrieveCheckInLimitations();

		this.checkInDatepicker.setOption( 'maxAdvanceDate', limitations.maxAdvanceDate );
	},
	/**
	 *
	 * @param {bool} setDate
	 * @returns {undefined}
	 */
	updateCheckOutLimitations: function( setDate ) {
		if ( typeof setDate === 'undefined' ) {
			setDate = true;
		}
		var limitations = this.retrieveCheckOutLimitations( this.checkInDatepicker.getDate(), this.checkOutDatepicker.getDate() );
		this.checkOutDatepicker.setOption( 'minDate', limitations.minDate );
		this.checkOutDatepicker.setOption( 'maxDate', limitations.maxDate );

		this.checkOutDatepicker.setDate( setDate ? limitations.date : null );
	},

 /**
 	*
	* @returns {Object} with keys
	*	- {Date} maxAdvanceDate
	*/
	retrieveCheckInLimitations: function() {
		var maxAdvanceDate = null;

		maxAdvanceDate = MPHB.HotelDataManager.myThis.reservationRules.getMaxAdvanceDate(this.checkInDatepicker.roomTypeId);

		return {
			maxAdvanceDate: maxAdvanceDate
		};

	},

	/**
	 *
	 * @param {type} checkInDate
	 * @param {type} checkOutDate
	 * @returns {Object} with keys
	 *	- {Date} minDate
	 *	- {Date} maxDate
	 *	- {Date|null} date
	 */
	retrieveCheckOutLimitations: function( checkInDate, checkOutDate ) {
		var minDate = MPHB.HotelDataManager.myThis.today;
		var maxDate = null;
		var recommendedDate = null;

		if ( checkInDate !== null ) {
			minDate = MPHB.HotelDataManager.myThis.reservationRules.getMinCheckOutDate( checkInDate, this.searchRoomTypeId );

			maxDate = MPHB.HotelDataManager.myThis.reservationRules.getMaxCheckOutDate( checkInDate, this.searchRoomTypeId );

			if (this.isDirectBooking) {
				maxDate = this.roomTypeData.getNearestLockedCheckOutDate( checkInDate, maxDate );
				maxDate = this.roomTypeData.getNearestHaveNotPriceDate( checkInDate, maxDate );
			}
			maxDate = MPHB.HotelDataManager.myThis.dateRules.getNearestNotStayInDate( checkInDate, maxDate );

			if ( this.isCheckOutDateNotValid( checkInDate, checkOutDate, minDate, maxDate ) ) {
				recommendedDate = this.retrieveRecommendedCheckOutDate( checkInDate, minDate, maxDate );
			} else {
				recommendedDate = checkOutDate;
			}
		}

		return {
			minDate: minDate,
			maxDate: maxDate,
			date: recommendedDate
		};
	},
	/**
	 *
	 * @param {Date} minDate
	 * @param {Date} maxDate
	 * @returns {Date|null}
	 */
	retrieveRecommendedCheckOutDate: function( checkInDate, minDate, maxDate ) {
		var recommendedDate = null;
		var expectedDate = MPHB.Utils.cloneDate( minDate );

		while ( MPHB.Utils.formatDateToCompare( expectedDate ) <= MPHB.Utils.formatDateToCompare( maxDate ) ) {

			var prevDay = $.datepick.add( MPHB.Utils.cloneDate( expectedDate ), -1, 'd' );

			if (
				!this.isCheckOutDateNotValid( checkInDate, expectedDate, minDate, maxDate ) &&
				(!this.isDirectBooking || this.roomTypeData.hasPriceForDate(prevDay))
			) {
				recommendedDate = expectedDate;
				break;
			}
			expectedDate = $.datepick.add( expectedDate, 1, 'd' );
		}

		return recommendedDate;

	},
	/**
	 *
	 * @param {Date} checkOutDate
	 * @param {Date} minDate
	 * @param {Date} maxDate
	 * @returns {Boolean}
	 */
	isCheckOutDateNotValid: function( checkInDate, checkOutDate, minDate, maxDate ) {
		return checkOutDate === null
			|| MPHB.Utils.formatDateToCompare( checkOutDate ) < MPHB.Utils.formatDateToCompare( minDate )
			|| MPHB.Utils.formatDateToCompare( checkOutDate ) > MPHB.Utils.formatDateToCompare( maxDate )
			|| !MPHB.HotelDataManager.myThis.reservationRules.isCheckOutSatisfy( checkInDate, checkOutDate, this.searchRoomTypeId )
			|| !MPHB.HotelDataManager.myThis.dateRules.canCheckOut( checkOutDate )
	},
	clearDatepickers: function() {
		this.checkInDatepicker.clear();
		this.checkOutDatepicker.clear();
	},
	refreshDatepickers: function() {
		this.checkInDatepicker.refresh();
		this.checkOutDatepicker.refresh();
	},
	/**
	 * See also MPHB.DirectBooking["input.mphb-datepick change"].
	 */
	onDatepickChange: function() {
		if ( this.directBooking != null ) {
			this.directBooking.hideSections();
		}
	}

} );

MPHB.RoomTypeCalendar = can.Control.extend( {}, {
	roomTypeData: null,
	roomTypeId: null,
	init: function( el, args ) {
		this.roomTypeId = parseInt( el.attr( 'id' ).replace( /^mphb-calendar-/, '' ) );
		this.roomTypeData = MPHB.HotelDataManager.myThis.getRoomTypeData( this.roomTypeId );

		var monthsToShow = MPHB._data.settings.numberOfMonthCalendar;
		var customMonths = el.attr( 'data-monthstoshow' );
		if ( customMonths ) {
			var customArray = customMonths.split( ',' );
			monthsToShow = ( customArray.length == 1 ) ? parseInt( customMonths ) : customArray;
		}
		var minDate = MPHB.HotelDataManager.myThis.reservationRules.getMinCheckInDate( this.roomTypeId );
		el.hide().datepick( {
			onDate: this.proxy(function( date, current ) {
				var dateData = {
					selectable: false,
					dateClass: 'mphb-date-cell',
					title: '',
					roomTypeId: this.roomTypeId
				};

				if ( current ) {
					dateData = this.roomTypeData.fillDateData( dateData, date );
				} else {
					dateData.dateClass += ' mphb-extra-date';
				}

				return dateData;
			}),
			minDate: minDate,
			monthsToShow: monthsToShow,
			firstDay: MPHB._data.settings.firstDay,
			pickerClass: MPHB._data.settings.datepickerClass,
            useMouseWheel: false
		} ).show();

		$( window ).on( 'mphb-update-room-type-data-' + this.roomTypeId, this.proxy(function( e ) {
			this.refresh();
		}) );

	},
	refresh: function() {
		this.element.hide();
		$.datepick._update( this.element[0], true );
		this.element.show();
	}

} );

/**
 *
 * @requires ./../datepicker.js
 */
MPHB.Datepicker( 'MPHB.RoomTypeCheckInDatepicker', {}, {
	isDirectBooking: false,
	init: function( el, args ) {
		this._super( el, args );
		this.isDirectBooking = MPHB._data.settings.isDirectBooking == '1' ? true : false;
		if(this.roomTypeId === 0) {
			this.roomTypeId = args.form.hasOwnProperty('roomTypeId') ? args.form.roomTypeId : 0;
		}
	},
	/**
	 *
	 * @returns {Object}
	 */
	getDatepickSettings: function() {
		return {
			minDate: MPHB.HotelDataManager.myThis.reservationRules.getMinCheckInDate(this.roomTypeId),
			onDate: this.proxy(function( date, current ) {
				var dateData = {
					dateClass: 'mphb-date-cell',
					selectable: false,
					title: '',
					roomTypeId: this.roomTypeId
				}

				if ( current ) {

					var earlierThanMinAdvance = MPHB.Utils.compareDates( date, MPHB.HotelDataManager.myThis.reservationRules.getMinCheckInDate(this.roomTypeId), '<' );

					var laterTnanMaxAdvance = this.getMaxAdvanceDate() !== null
                        && MPHB.Utils.compareDates( date, this.getMaxAdvanceDate(), '>' );

					var canCheckIn = !earlierThanMinAdvance && !laterTnanMaxAdvance
                        && MPHB.HotelDataManager.myThis.reservationRules.isCheckInSatisfy( date, this.roomTypeId )
						&& MPHB.HotelDataManager.myThis.dateRules.canCheckIn( date );

					if ( this.isDirectBooking ) {
						dateData = this.form.roomTypeData.fillDateData( dateData, date, 'checkIn' );
						canCheckIn = canCheckIn && this.form.roomTypeData.canCheckIn( date );
					} else {
						if (this.form.roomTypeData.isEarlierThanToday(date)) {
							dateData.dateClass += ' mphb-past-date';
							dateData.title += ' ' + MPHB._data.translations.past;
						}
						dateData = MPHB.HotelDataManager.myThis.fillDateCellData(dateData, date, 'checkIn');
					}

					if ( canCheckIn ) {
						dateData.selectable = true;
					}

				} else {
					dateData.dateClass += ' mphb-extra-date';
				}

				if ( dateData.selectable ) {
					dateData.dateClass += ' mphb-date-selectable';
				} else {
					dateData.dateClass += ' mphb-unselectable-date';
				}

				return dateData;
			}),
			onSelect: this.proxy(function( dates ) {
				this.form.updateCheckOutLimitations();
				this.form.onDatepickChange();
			}),
			pickerClass: 'mphb-datepick-popup mphb-check-in-datepick ' + MPHB._data.settings.datepickerClass,
		};
	},
	/**
	 * @param {Date} date
	 */
	setDate: function( date ) {

		if ( date == null ) {
			return this._super( date );
		}

		if ( !MPHB.HotelDataManager.myThis.reservationRules.isCheckInSatisfy( date, this.roomTypeId ) ) {
			return this._super( null );
		}

		if ( !MPHB.HotelDataManager.myThis.dateRules.canCheckIn( date ) ) {
			return this._super( null );
		}

		return this._super( date );
	}

} );

/**
 *
 * @requires ./../datepicker.js
 */
MPHB.RoomTypeCheckOutDatepicker = MPHB.Datepicker.extend( {}, {
	/**
	 *
	 * @returns {Object}
	 */
	getDatepickSettings: function() {
		return {
			onDate: this.proxy(function( date, current ) {
				var dateData = {
					dateClass: 'mphb-date-cell',
					selectable: false,
					title: '',
					roomTypeId: this.roomTypeId
				};
				if ( current ) {
					var checkInDate = this.form.checkInDatepicker.getDate();
					var earlierThanMin = this.getMinDate() !== null && MPHB.Utils.formatDateToCompare( date ) < MPHB.Utils.formatDateToCompare( this.getMinDate() );
					var laterThanMax = this.getMaxDate() !== null && MPHB.Utils.formatDateToCompare( date ) > MPHB.Utils.formatDateToCompare( this.getMaxDate() );

					if ( checkInDate !== null && MPHB.Utils.formatDateToCompare( date ) === MPHB.Utils.formatDateToCompare( checkInDate ) ) {
						dateData.dateClass += ' mphb-check-in-date';
						dateData.title += MPHB._data.translations.checkInDate;
					}

					if ( earlierThanMin ) {
						var minStayDate = checkInDate ? MPHB.HotelDataManager.myThis.reservationRules.getMinCheckOutDate( checkInDate, this.roomTypeId ) : false;
						if ( MPHB.Utils.formatDateToCompare( date ) < MPHB.Utils.formatDateToCompare( checkInDate ) ) {
							dateData.dateClass += ' mphb-earlier-min-date mphb-earlier-check-in-date';
						} else if ( minStayDate && MPHB.Utils.formatDateToCompare( date ) < MPHB.Utils.formatDateToCompare( minStayDate ) ) {
							dateData.dateClass += ' mphb-earlier-min-date';
							dateData.title += (dateData.title.length ? ' ' : '') + MPHB._data.translations.lessThanMinDaysStay;
						}
					}

					if ( laterThanMax ) {
						var maxStayDate = checkInDate ? MPHB.HotelDataManager.myThis.reservationRules.getMaxCheckOutDate( checkInDate, this.roomTypeId ) : false;
						if ( !maxStayDate || MPHB.Utils.formatDateToCompare( date ) < MPHB.Utils.formatDateToCompare( maxStayDate ) ) {
							dateData.title += (dateData.title.length ? ' ' : '') + MPHB._data.translations.laterThanMaxDate;
						} else {
							dateData.title += (dateData.title.length ? ' ' : '') + MPHB._data.translations.moreThanMaxDaysStay;
						}
						dateData.dateClass += ' mphb-later-max-date';
					}

					if (this.isDirectBooking) {
						dateData = this.form.roomTypeData.fillDateData( dateData, date, 'checkOut', checkInDate );
					} else {
						if (this.form.roomTypeData.isEarlierThanToday(date)) {
							dateData.dateClass += ' mphb-past-date';
							dateData.title += ' ' + MPHB._data.translations.past;
						}
						dateData = MPHB.HotelDataManager.myThis.fillDateCellData(dateData, date, 'checkOut', checkInDate);
					}

					var canCheckOut = !earlierThanMin && !laterThanMax &&
						MPHB.HotelDataManager.myThis.reservationRules.isCheckOutSatisfy( checkInDate, date, this.roomTypeId ) &&
						MPHB.HotelDataManager.myThis.dateRules.canCheckOut( date );

					if ( canCheckOut ) {
						dateData.selectable = true;
					}
				} else {
					dateData.dateClass += ' mphb-extra-date';
				}

				if ( dateData.selectable ) {
					dateData.dateClass += ' mphb-selectable-date';
				} else {
					dateData.dateClass += ' mphb-unselectable-date';
				}

				return dateData;
			}),
			onSelect: this.proxy(function( dates ) {
				this.form.onDatepickChange();
			}),
			pickerClass: 'mphb-datepick-popup mphb-check-out-datepick ' + MPHB._data.settings.datepickerClass,
		};
	},
	/**
	 * @param {Date} date
	 */
	setDate: function( date ) {

		if ( date == null ) {
			return this._super( date );
		}

		var checkInDate = this.form.checkInDatepicker.getDate();

		if ( !MPHB.HotelDataManager.myThis.reservationRules.isCheckOutSatisfy( checkInDate, date, this.roomTypeId ) ) {
			return this._super( null );
		}

		if ( !MPHB.HotelDataManager.myThis.dateRules.canCheckOut( date ) ) {
			return this._super( null );
		}

		return this._super( date );
	},
} );

MPHB.RoomTypeData = can.Construct.extend( {}, {
        id: null,
        originalId: null,
        bookedDates: {},
        checkInDates: {},
        checkOutDates: {},
        blockedDates: {}, // Blocked by custom rules. { %Date%: %Blocked rooms count% }
        havePriceDates: {},
        activeRoomsCount: 0,
        /**
         *
         * @param {Object}      data
         * @param {Object}      data.bookedDates
         * @param {Object}      data.havePriceDates
         * @param {int}         data.activeRoomsCount
         * @returns {undefined}
         */
        init: function( id, data ) {
                this.id = id;
                this.originalId = data.originalId;
                this.setRoomsCount( data.activeRoomsCount );
                this.setDates( data.dates );
        },
        update: function( data ) {
                if ( data.hasOwnProperty( 'activeRoomsCount' ) ) {
                        this.setRoomsCount( data.activeRoomsCount );
                }

                if ( data.hasOwnProperty( 'dates' ) ) {
                        this.setDates( data.dates );
                }

                $( window ).trigger( 'mphb-update-room-type-data-' + this.id );
        },
        /**
         *
         * @param {int} count
         * @returns {undefined}
         */
        setRoomsCount: function( count ) {
                this.activeRoomsCount = count;
        },
        /**
         *
         * @param {Object} dates
         * @param {Object} dates.bookedDates
         * @param {Object} dates.havePriceDates
         * @returns {undefined}
         */
        setDates: function( dates ) {

                this.bookedDates = dates.hasOwnProperty( 'booked' ) ? dates.booked : {};
                this.checkInDates = dates.hasOwnProperty( 'checkIns' ) ? dates.checkIns : {};
                this.checkOutDates = dates.hasOwnProperty( 'checkOuts' ) ? dates.checkOuts : {};
                this.blockedDates = dates.hasOwnProperty( 'blocked' ) ? dates.blocked : {};
                this.havePriceDates = dates.hasOwnProperty( 'havePrice' ) ? dates.havePrice : {};
        },
        blockAllRoomsOnDate: function( dateFormatted ){
                this.blockedDates[dateFormatted] = this.activeRoomsCount;
        },
        /**
         *
         * @param {Date} checkInDate
         * @param {Date} stopDate
         * @returns {Date|false} Nearest locked room date if exists or false otherwise.
         */
        getNearestLockedCheckOutDate: function( checkInDate, stopDate ) {
                var nearestDate = stopDate;
                var activeRoomsCount = this.activeRoomsCount;

                var startDateFormatted = $.datepick.formatDate( 'yyyy-mm-dd', checkInDate );
                var stopDateFormatted = $.datepick.formatDate( 'yyyy-mm-dd', stopDate );

                $.each( this.getLockedCheckoutDates(), function( dateFormatted, lockedRoomsCount ) {

                        if ( stopDateFormatted < dateFormatted ) {
                                return false; // break;
                        }

                        if ( startDateFormatted > dateFormatted ) {
                                return true; // continue
                        }

                        if ( lockedRoomsCount >= activeRoomsCount ) {
                                nearestDate = $.datepick.parseDate( 'yyyy-mm-dd', dateFormatted );
                                return false; // break
                        }

                } );

                return nearestDate;
        },
        /**
         *
         * @returns {Object}
         */
        getLockedCheckoutDates: function() {
                var dates = $.extend( {}, this.bookedDates );
                $.each( this.blockedDates, function( dateFormatted, blockedRoomsCount ) {
                        if ( !dates.hasOwnProperty( dateFormatted ) ) {
                                dates[dateFormatted] = blockedRoomsCount;
                        } else {
                                dates[dateFormatted] += blockedRoomsCount;
                        }
                } );
                return dates;
        },
        /**
         *
         * @param {Date} dateFrom
         * @param {Date} stopDate
         * @returns {Date}
         */
        getNearestHaveNotPriceDate: function( dateFrom, stopDate ) {
                var nearestDate = MPHB.Utils.cloneDate( stopDate );
                var expectedDate = MPHB.Utils.cloneDate( dateFrom );

                while ( MPHB.Utils.formatDateToCompare( expectedDate ) <= MPHB.Utils.formatDateToCompare( stopDate ) ) {
                        if ( !this.hasPriceForDate( expectedDate ) ) {
                                nearestDate = expectedDate;
                                break;
                        }
                        expectedDate = $.datepick.add( expectedDate, 1, 'd' );
                }

                return nearestDate;
        },
        /**
         *
         * @returns {Object}
         */
        getHavePriceDates: function() {
                var dates = {};
                return $.extend( dates, this.havePriceDates );
        },
        /**
         *
         * @param {Date}
         * @returns {String}
         */
        getDateStatus: function( date ) {
                var status = MPHB.HotelDataManager.ROOM_STATUS_AVAILABLE;

            		if ( this.isEarlierThanToday( date ) ) {
            			status = MPHB.HotelDataManager.ROOM_STATUS_PAST;
            		} else if ( this.isDateBooked( date ) ) {
            			status = MPHB.HotelDataManager.ROOM_STATUS_BOOKED;
            		} else if( MPHB.HotelDataManager.myThis.isEarlierThanMinAdvanceDate( date, this.originalId ) ) {
            			status = MPHB.HotelDataManager.ROOM_STATUS_EARLIER_MIN_ADVANCE;
            		} else if( MPHB.HotelDataManager.myThis.isLaterThamMaxAdvanceDate( date, this.originalId ) ) {
            			status = MPHB.HotelDataManager.ROOM_STATUS_LATER_MAX_ADVANCE;
            		} else if ( !this.hasPriceForDate( date ) ) {
            			status = MPHB.HotelDataManager.ROOM_STATUS_NOT_AVAILABLE;
            		} else if ( !this.getAvailableRoomsCount( date ) ) {
            			status = MPHB.HotelDataManager.ROOM_STATUS_NOT_AVAILABLE;
            		}

                return status;
        },
        canCheckIn: function( date ) {
                var status = this.getDateStatus( date );
                if ( status == MPHB.HotelDataManager.ROOM_STATUS_AVAILABLE ) {
                        return true;
                } else {
                        return false;
                }
        },
        /**
         *
         * @param {Date} date
         * @returns {Boolean}
         */
        isDateBooked: function( date ) {
                var dateFormatted = $.datepick.formatDate( 'yyyy-mm-dd', date );
                return this.bookedDates.hasOwnProperty( dateFormatted ) && this.bookedDates[dateFormatted] >= this.activeRoomsCount;
        },
        /**
         *
         * @param {Date} date
         * @returns {Boolean}
         */
        isDateBookingCheckIn: function( date ) {
                var dateFormatted = $.datepick.formatDate( 'yyyy-mm-dd', date );
                return this.checkInDates.hasOwnProperty( dateFormatted );
        },
        /**
         *
         * @param {Date} date
         * @returns {Boolean}
         */
        isDateBookingCheckOut: function( date ) {
                var dateFormatted = $.datepick.formatDate( 'yyyy-mm-dd', date );

                if ( !this.checkOutDates.hasOwnProperty( dateFormatted ) ) {
                        return false;
                }

                if ( this.bookedDates.hasOwnProperty( dateFormatted ) ) {
                        var usedCount = this.checkOutDates[dateFormatted] + this.bookedDates[dateFormatted];
                        return usedCount >= this.activeRoomsCount;
                } else {
                        return this.checkOutDates[dateFormatted] >= this.activeRoomsCount;
                }
        },
        /**
         *
         * @param {Date} date
         * @returns {Boolean}
         */
        hasPriceForDate: function( date ) {
                var dateFormatted = $.datepick.formatDate( 'yyyy-mm-dd', date );
                return MPHB.Utils.inArray( dateFormatted, this.havePriceDates );
        },
        /**
         *
         * @param {Date} date
         * @returns {int}
         */
        getAvailableRoomsCount: function( date ) {
                var dateFormatted = $.datepick.formatDate( 'yyyy-mm-dd', date );
                var count = this.activeRoomsCount;

                if ( this.bookedDates.hasOwnProperty( dateFormatted ) ) {
                        count -= this.bookedDates[dateFormatted];
                }

                if ( this.blockedDates.hasOwnProperty( dateFormatted ) ) {
                        count -= this.blockedDates[dateFormatted];
                }

                if ( count < 0 ) {
                        count = 0;
                }

                return count;
        },
        /**
         *
         * @param {Object} dateData
         * @param {Date} date
         * @param {string} [type=""] checkIn, checkOut or empty string
         * @param {Date} [dateForRules]
         * @returns {Object}
         */
        fillDateData: function( dateData, date, type, dateForRules ) {
                if (!dateForRules) {
                        dateForRules = date;
                }
                var status = this.getDateStatus( date );
                var titles = [ ];
                var classes = [ ];

            		switch ( status ) {
            			case MPHB.HotelDataManager.ROOM_STATUS_PAST:
            				classes.push( 'mphb-past-date' );
            				titles.push( MPHB._data.translations.past );
            				break;
            			case MPHB.HotelDataManager.ROOM_STATUS_AVAILABLE:
            				classes.push( 'mphb-available-date' );
            				titles.push( MPHB._data.translations.available + '(' + this.getAvailableRoomsCount( date ) + ')' );
            				if ( this.isDateBookingCheckOut( date ) ) {
            					classes.push( 'mphb-date-check-out' );
            				}
            				break;
            			case MPHB.HotelDataManager.ROOM_STATUS_NOT_AVAILABLE:
            				classes.push( 'mphb-not-available-date' );
            				titles.push( MPHB._data.translations.notAvailable );
            				break;
            			case MPHB.HotelDataManager.ROOM_STATUS_BOOKED:
            				classes.push( 'mphb-booked-date' );
            				titles.push( MPHB._data.translations.booked );
            				if ( this.isDateBookingCheckIn( date ) ) {
            					classes.push( 'mphb-date-check-in' );
            				}
            				if ( this.isDateBookingCheckOut( date ) ) {
            					classes.push( 'mphb-date-check-out' );
            				}
            				break;
            			case MPHB.HotelDataManager.ROOM_STATUS_EARLIER_MIN_ADVANCE:
            			case MPHB.HotelDataManager.ROOM_STATUS_LATER_MAX_ADVANCE:
            				titles.push( MPHB._data.translations.notAvailable );
            				if ( this.isDateBookingCheckOut( date ) ) {
            					classes.push( 'mphb-booked-date' );
            					classes.push( 'mphb-date-check-out' );
            					classes.push( 'mphb-available-date' );
            				}
            				break;
            		}

                dateData.dateClass += (dateData.dateClass.length ? ' ' : '') + classes.join( ' ' );
                dateData.title += (dateData.title.length ? ', ' : '') + titles.join( ', ' );

                dateData = MPHB.HotelDataManager.myThis.fillDateCellData( dateData, date, type, dateForRules );

                return dateData;
        },
        appendRulesToTitle: function( date, title ) {
                var rulesTitles = [ ];

                if ( !MPHB.HotelDataManager.myThis.dateRules.canStayIn( date ) ) {
                        rulesTitles.push( MPHB._data.translations.notStayIn );
                }
                if ( !MPHB.HotelDataManager.myThis.dateRules.canCheckIn( date ) ) {
                        rulesTitles.push( MPHB._data.translations.notCheckIn );
                }
                if ( !MPHB.HotelDataManager.myThis.dateRules.canCheckOut( date ) ) {
                        rulesTitles.push( MPHB._data.translations.notCheckOut );
                }

                if ( rulesTitles.length ) {
                        title += ' ' + MPHB._data.translations.rules + ' ' + rulesTitles.join( ', ' );
                }

                return title;
        },
        /**
         *
         * @param {Date} date
         * @returns {Boolean}
         */
        isEarlierThanToday: function( date ) {
                return MPHB.Utils.formatDateToCompare( date ) < MPHB.Utils.formatDateToCompare( MPHB.HotelDataManager.myThis.today );
        },

} );

/**
 * @requires ./basic-rule.js
 */
/**
 * @class MPHB.Rules.MaxAdvanceDaysRule
 */
MPHB.Rules.BasicRule('MPHB.Rules.MaxAdvanceDaysRule', {}, {
	/**
	 * @var {number}
	 */
	max: null,
	/**
	 *
	 * @param {{season_ids: number[], room_type_ids: number[], max_advance_reservation: number}} data
	 */
	init: function(data) {
		this._super(data);
		this.max = data.max_advance_reservation != 0 ? data.max_advance_reservation : 3652; // 10 years
	},
	/**
	 *
	 * @param {Date} checkInDate
	 * @param {Date} checkOutDate
	 * @return {boolean}
	 */
	verify: function(checkInDate, checkOutDate) {
		var maxCheckInDate = $.datepick.add(MPHB.Utils.cloneDate(MPHB.HotelDataManager.myThis.today), this.max, 'd');
		return MPHB.Utils.compareDates(checkInDate, maxCheckInDate, '<=');
	}

});

/**
 * @requires ./basic-rule.js
 */
/**
 * @class MPHB.Rules.MinAdvanceDaysRule
 */
MPHB.Rules.BasicRule('MPHB.Rules.MinAdvanceDaysRule', {}, {
	/**
	 * @var {number}
	 */
	min: null,
	/**
	 *
	 * @param {{season_ids: number[], room_type_ids: number[], min_advance_reservation: number}} data
	 */
	init: function(data) {
		this._super(data);
		this.min = data.min_advance_reservation;
	},
	/**
	 *
	 * @param {Date} checkInDate
	 * @param {Date} checkOutDate
	 * @return {boolean}
	 */
	verify: function(checkInDate, checkOutDate) {
		var minCheckInDate = $.datepick.add(MPHB.Utils.cloneDate(MPHB.HotelDataManager.myThis.today), this.min, 'd');
		return MPHB.Utils.compareDates(minCheckInDate, checkInDate, '<=');
	}
});

/**
 *
 * @requires ./../datepicker.js
 */
MPHB.SearchCheckInDatepicker = MPHB.Datepicker.extend( {}, {
	/**
	 *
	 * @returns {Object}
	 */
	getDatepickSettings: function() {
		return {
			minDate: MPHB.HotelDataManager.myThis.reservationRules.getMinCheckInDate(this.roomTypeId),
			onSelect: this.proxy(function( dates ) {
				this.form.updateCheckOutLimitations();
			}),
			onDate: this.proxy(function( date, current ) {
				var dateData = {
					dateClass: 'mphb-date-cell',
					selectable: false,
					title: ''
				};

				var laterTnanMaxAdvance = this.getMaxAdvanceDate() !== null
                    && MPHB.Utils.compareDates( date, this.getMaxAdvanceDate(), '>' );

				var earlierThanMinAdvance = MPHB.Utils.compareDates( date, this.getMinDate(), '<' );

				if ( current ) {

					var canCheckIn = !laterTnanMaxAdvance && !earlierThanMinAdvance
                        && MPHB.HotelDataManager.myThis.reservationRules.isCheckInSatisfy( date, this.roomTypeId )
                        && MPHB.HotelDataManager.myThis.dateRules.canCheckIn( date );

					if ( canCheckIn ) {
						dateData.selectable = true;
					}

					dateData = MPHB.HotelDataManager.myThis.fillDateCellData( dateData, date, 'checkIn' );

				} else {
					dateData.dateClass += ' mphb-extra-date';
				}

				if ( dateData.selectable ) {
					dateData.dateClass += ' mphb-selectable-date';
				} else {
					dateData.dateClass += ' mphb-unselectable-date';
				}

				return dateData;
			}),
			pickerClass: 'mphb-datepick-popup mphb-check-in-datepick ' + MPHB._data.settings.datepickerClass,
		};
	}
} );

/**
 *
 * @requires ./../datepicker.js
 */
MPHB.SearchCheckOutDatepicker = MPHB.Datepicker.extend( {}, {
	/**
	 *
	 * @returns {Object}
	 */
	getDatepickSettings: function() {
		return {
			onDate: this.proxy(function( date, current ) {
				var dateData = {
					dateClass: 'mphb-date-cell',
					selectable: false,
					title: ''
				};

				if ( current ) {

					var checkInDate = this.form.checkInDatepicker.getDate();
					var earlierThanMin = this.getMinDate() !== null && MPHB.Utils.formatDateToCompare( date ) < MPHB.Utils.formatDateToCompare( this.getMinDate() );
					var laterThanMax = this.getMaxDate() !== null && MPHB.Utils.formatDateToCompare( date ) > MPHB.Utils.formatDateToCompare( this.getMaxDate() );

					if ( checkInDate !== null && MPHB.Utils.formatDateToCompare( date ) === MPHB.Utils.formatDateToCompare( checkInDate ) ) {
						dateData.dateClass += ' mphb-check-in-date';
						dateData.title += MPHB._data.translations.checkInDate;
					}

					if ( earlierThanMin ) {
						if ( MPHB.Utils.formatDateToCompare( date ) < MPHB.Utils.formatDateToCompare( checkInDate ) ) {
							dateData.dateClass += ' mphb-earlier-min-date mphb-earlier-check-in-date';
						} else {
							dateData.dateClass += ' mphb-earlier-min-date';
							dateData.title += (dateData.title.length ? ' ' : '') + MPHB._data.translations.lessThanMinDaysStay;
						}
					}

					if ( laterThanMax ) {
						var maxStayDate = checkInDate ? MPHB.HotelDataManager.myThis.reservationRules.getMaxCheckOutDate( checkInDate, this.roomTypeId ) : false;
						if ( !maxStayDate || MPHB.Utils.formatDateToCompare( date ) < MPHB.Utils.formatDateToCompare( maxStayDate ) ) {
							dateData.title += (dateData.title.length ? ' ' : '') + MPHB._data.translations.laterThanMaxDate;
						} else {
							dateData.title += (dateData.title.length ? ' ' : '') + MPHB._data.translations.moreThanMaxDaysStay;
						}
						dateData.dateClass += ' mphb-later-max-date';
					}

					dateData = MPHB.HotelDataManager.myThis.fillDateCellData( dateData, date, 'checkOut', checkInDate );

					var canCheckOut = !earlierThanMin && !laterThanMax &&
						MPHB.HotelDataManager.myThis.reservationRules.isCheckOutSatisfy( checkInDate, date, this.roomTypeId ) &&
						MPHB.HotelDataManager.myThis.dateRules.canCheckOut( date );

					if ( canCheckOut ) {
						dateData.selectable = true;
					}

				} else {
					dateData.dateClass += ' mphb-extra-date';
				}

				if ( dateData.selectable ) {
					dateData.dateClass += ' mphb-selectable-date';
				} else {
					dateData.dateClass += ' mphb-unselectable-date';
				}

				return dateData;
			}),
			pickerClass: 'mphb-datepick-popup mphb-check-out-datepick ' + MPHB._data.settings.datepickerClass,
		};
	}
} );

MPHB.SearchForm = can.Control.extend( {}, {
	checkInDatepickerEl: null,
	checkOutDatepickerEl: null,
	checkInDatepicker: null,
	checkOutDatepicker: null,
	init: function( el, args ) {

		this.checkInDatepickerEl = this.element.find( '.mphb-datepick[id^="mphb_check_in_date"]' );
		this.checkOutDatepickerEl = this.element.find( '.mphb-datepick[id^="mphb_check_out_date"]' );

		this.checkInDatepicker = new MPHB.SearchCheckInDatepicker( this.checkInDatepickerEl, {'form': this} );
		this.checkOutDatepicker = new MPHB.SearchCheckOutDatepicker( this.checkOutDatepickerEl, {'form': this} );

		// Enable reservation rules on check-in date
		this.updateCheckInLimitations();

		// Enable reservation rules on check-out date
		if ( this.checkInDatepicker.getDate() ) {
			this.updateCheckOutLimitations();
		}

	},

	updateCheckInLimitations: function() {
		var limitations = this.retrieveCheckInLimitations();

		this.checkInDatepicker.setOption( 'maxAdvanceDate', limitations.maxAdvanceDate );
	},
	/**
	 *
	 * @param {bool} [setDate=true]
	 * @returns {undefined}
	 */
	updateCheckOutLimitations: function( setDate ) {
		if ( typeof setDate === 'undefined' ) {
			setDate = true;
		}
		var limitations = this.retrieveCheckOutLimitations( this.checkInDatepicker.getDate(), this.checkOutDatepicker.getDate() );

		this.checkOutDatepicker.setOption( 'minDate', limitations.minDate );
		this.checkOutDatepicker.setOption( 'maxDate', limitations.maxDate );

		this.checkOutDatepicker.setDate( setDate ? limitations.date : null );
	},
	retrieveCheckOutLimitations: function( checkInDate, checkOutDate ) {

		var minDate = MPHB.HotelDataManager.myThis.today;
		var maxDate = null;
		var recommendedDate = null;

		if ( checkInDate !== null ) {
			var minDate = MPHB.HotelDataManager.myThis.reservationRules.getMinCheckOutDate( checkInDate );

			var maxDate = MPHB.HotelDataManager.myThis.reservationRules.getMaxCheckOutDate( checkInDate );
			maxDate = MPHB.HotelDataManager.myThis.dateRules.getNearestNotStayInDate( checkInDate, maxDate );

			if ( this.isCheckOutDateNotValid( checkInDate, checkOutDate, minDate, maxDate ) ) {
				recommendedDate = this.retrieveRecommendedCheckOutDate( checkInDate, minDate, maxDate );
			} else {
				recommendedDate = checkOutDate;
			}

		}

		return {
			minDate: minDate,
			maxDate: maxDate,
			date: recommendedDate
		};
	},

 	retrieveCheckInLimitations: function() {
 		var maxAdvanceDate = null;

 		maxAdvanceDate = MPHB.HotelDataManager.myThis.reservationRules.getMaxAdvanceDate(this.checkInDatepicker.roomTypeId);

 		return {
 			maxAdvanceDate: maxAdvanceDate
 		};

 	},

	retrieveRecommendedCheckOutDate: function( checkInDate, minDate, maxDate ) {
		var recommendedDate = null;
		var expectedDate = MPHB.Utils.cloneDate( minDate );

		while ( MPHB.Utils.formatDateToCompare( expectedDate ) <= MPHB.Utils.formatDateToCompare( maxDate ) ) {
			if ( !this.isCheckOutDateNotValid( checkInDate, expectedDate, minDate, maxDate ) ) {
				recommendedDate = expectedDate;
				break;
			}
			expectedDate = $.datepick.add( expectedDate, 1, 'd' );
		}

		return recommendedDate;

	},
	isCheckOutDateNotValid: function( checkInDate, checkOutDate, minDate, maxDate ) {
		return checkOutDate === null
			|| MPHB.Utils.formatDateToCompare( checkOutDate ) < MPHB.Utils.formatDateToCompare( minDate )
			|| MPHB.Utils.formatDateToCompare( checkOutDate ) > MPHB.Utils.formatDateToCompare( maxDate )
			|| !MPHB.HotelDataManager.myThis.reservationRules.isCheckOutSatisfy( checkInDate, checkOutDate )
			|| !MPHB.HotelDataManager.myThis.dateRules.canCheckOut( checkOutDate );
	}

} );

MPHB.RoomBookSection = can.Control.extend( {}, {
	roomTypeId: null,
	roomTitle: '',
	roomPrice: 0,
	quantitySelect: null,
	bookButton: null,
	confirmButton: null,
	removeButton: null,
	messageHolder: null,
	messageWrapper: null,
	form: null,
	init: function( el, args ) {
		this.reservationCart = args.reservationCart;
		this.roomTypeId = parseInt( el.attr( 'data-room-type-id' ) );
		this.roomTitle = el.attr( 'data-room-type-title' );
		this.roomPrice = parseFloat( el.attr( 'data-room-price' ) );
		this.confirmButton = el.find( '.mphb-confirm-reservation' );
		this.quantitySelect = el.find( '.mphb-rooms-quantity' );
		this.messageWrapper = el.find( '.mphb-rooms-reservation-message-wrapper' );
		this.messageHolder = el.find( '.mphb-rooms-reservation-message' );
	},
	/**
	 *
	 * @returns {int}
	 */
	getRoomTypeId: function() {
		return this.roomTypeId;
	},
	/**
	 *
	 * @returns {Number}
	 */
	getPrice: function() {
		return this.roomPrice;
	},
	'.mphb-book-button click': function( button, e ) {
		e.preventDefault();
		e.stopPropagation();

		var quantity = parseInt( this.quantitySelect.val() );
		this.reservationCart.addToCart( this.roomTypeId, quantity );

        if (!MPHB._data.settings.isDirectBooking) {
            // Add message "N x ... has/have been added to your reservation."
            var messagePattern = ( 1 == quantity ) ? MPHB._data.translations.roomsAddedToReservation_singular : MPHB._data.translations.roomsAddedToReservation_plural;
            var message = messagePattern.replace( '%1$d', quantity ).replace( '%2$s', this.roomTitle );
            this.messageHolder.html( message );

            // Show "N x ... has/have been added to your reservation." message
            // Show "Remove" button
            // Show "Confirm Reservation" button
            this.element.addClass( 'mphb-rooms-added' );

        } else {
            button.prop('disabled', true);

            // Go to the Checkout immediately
            this.reservationCart.confirmReservation();
        }
	},
	'.mphb-remove-from-reservation click': function( el, e ) {
		e.preventDefault();
		e.stopPropagation();

		this.reservationCart.removeFromCart( this.roomTypeId );

		this.messageHolder.empty();
		this.element.removeClass( 'mphb-rooms-added' );
	},
	'.mphb-confirm-reservation click': function( el, e ) {
		e.preventDefault();
		e.stopPropagation();
		this.reservationCart.confirmReservation();
	}
} );
/**
 *
 * @requires ./room-book-section.js
 */
MPHB.ReservationCart = can.Control.extend( {}, {
	cartForm: null,
	cartDetails: null,
	roomBookSections: {},
	cartContents: {},
	init: function( el, args ) {
		this.cartForm = el.find( '#mphb-reservation-cart' );
		this.cartDetails = el.find( '.mphb-reservation-details' );
		this.initRoomBookSections( el.find( '.mphb-reserve-room-section' ) );
	},
	initRoomBookSections: function( sections ) {
		var self = this;
		var bookSection;
		$.each( sections, function( index, roomSection ) {
			bookSection = new MPHB.RoomBookSection( $( roomSection ), {
				reservationCart: self,
			} );
			self.roomBookSections[bookSection.getRoomTypeId()] = bookSection;
		} );
	},
	addToCart: function( roomTypeId, quantity ) {
		this.cartContents[roomTypeId] = quantity;
		this.updateCartView();
		this.updateCartInputs();
	},
	removeFromCart: function( roomTypeId ) {
		delete this.cartContents[roomTypeId];
		this.updateCartView();
		this.updateCartInputs();
	},
	calcRoomsInCart: function() {
		var count = 0;

		$.each( this.cartContents, function( roomTypeId, quantity ) {
			count += quantity;
		} );

		return count;
	},
	calcTotalPrice: function() {
		var total = 0;
		var price = 0;
		var self = this;

		$.each( this.cartContents, function( roomTypeId, quantity ) {
			price = self.roomBookSections[roomTypeId].getPrice();
			total += price * quantity;
		} );

		return total;
	},
	updateCartView: function() {
		if ( !$.isEmptyObject( this.cartContents ) ) {

			var roomsCount = this.calcRoomsInCart();
			var messageTemplate = ( 1 == roomsCount ) ? MPHB._data.translations.countRoomsSelected_singular : MPHB._data.translations.countRoomsSelected_plural;
			var cartMessage = messageTemplate.replace( '%s', roomsCount )
			this.cartDetails.find( '.mphb-cart-message' ).html( cartMessage );

			var total = this.calcTotalPrice();
			var totalMessage = MPHB.format_price( total, { 'trim_zeros': true } );
			this.cartDetails.find( '.mphb-cart-total-price>.mphb-cart-total-price-value' ).html( totalMessage );

			this.cartForm.removeClass( 'mphb-empty-cart' );
		} else {
			this.cartForm.addClass( 'mphb-empty-cart' );
		}
	},
	updateCartInputs: function() {
		// empty inputs
		this.cartForm.find( '[name^="mphb_rooms_details"]' ).remove();

		var self = this;
		$.each( this.cartContents, function( roomTypeId, quantity ) {
			var input = $( '<input />', {
				name: 'mphb_rooms_details[' + roomTypeId + ']',
				type: 'hidden',
				value: quantity

			} );
			self.cartForm.prepend( input );
		} );
	},
	confirmReservation: function() {
		this.cartForm.submit();
	},
} );


/**
 * @requires ../stripe-gateway.js
 *
 * @since 3.6.0
 */
MPHB.StripeGateway.PaymentMethods = can.Construct.extend(
    {},
    {
        listAll: ['card', 'bancontact', 'ideal', 'giropay', 'sepa_debit', 'sofort'],
        listEnabled: ['card'],

        paymentMethods: {},

        currentPayment: 'card',
        currentCountry: '',

        inputs: null, // input[name="stripe_payment_method"] elements

        isMounted: false,

        init: function (enabledPayments, defaultCountry) {
            this.listEnabled = enabledPayments.slice(0); // Clone array

            this.initPayments();

            // Change the country only when paymentMethods data are fully ready
            this.selectCountry(defaultCountry);
        },

        initPayments: function () {
            var self = this;

            this.forEach(function (payment) {
                self.paymentMethods[payment] = {
                    isEnabled: self.listEnabled.indexOf(payment) >= 0,
                    nav:       null, // .mphb-stripe-payment-method.%payment% element
                    fields:    null  // .mphb-stripe-payment-fields.%payment% element
                };
            });
        },

        selectPayment: function (payment) {
            if (payment == this.currentPayment || !this.paymentMethods.hasOwnProperty(payment)) {
                return;
            }

            this.togglePayment(this.currentPayment, false);
            this.togglePayment(payment, true);

            this.currentPayment = payment;
        },

        togglePayment: function (payment, enable) {
            if (this.isMounted) {
                this.paymentMethods[payment].nav.toggleClass('active', enable);
                this.paymentMethods[payment].fields.toggleClass('mphb-hide', !enable);
            }
        },

        selectCountry: function (country) {
            if (this.currentCountry == country) {
                return;
            }

            this.currentCountry = country;
            this.currentPayment = 'card'; // Reset selected payment method

            this.showRelevantMethods();
        },

        showRelevantMethods: function () {
            if (!this.isMounted) {
                return;
            }

            var selectedPayment = this.currentPayment;

            this.forEach(function (payment, paymentMethod) {
                // Show only fields of the selected payment method
                paymentMethod.fields.toggleClass('mphb-hide', payment != selectedPayment);
            });

            // Select proper radio button
            this.inputs.val([selectedPayment]);
        },

        mount: function (section) {
            this.forEach(function (payment, paymentMethod) {
                paymentMethod.nav = section.find('.mphb-stripe-payment-method.' + payment);
                paymentMethod.fields = section.find('.mphb-stripe-payment-fields.' + payment);
            });

            this.inputs = section.find('input[name="stripe_payment_method"]');

            this.isMounted = true;
        },

        unmount: function () {
            this.forEach(function (_, paymentMethod) {
                paymentMethod.nav = null;
                paymentMethod.fields = null;
            });

            this.inputs = null;

            this.isMounted = false;
        },

        forEach: function (callback) {
            var self = this;

            this.listAll.forEach(function (payment) {
                // callback(string, Object, MPHB.StripeGateway.PaymentMethods)
                callback(payment, self.paymentMethods[payment], self);
            });
        },

        isEnabled: function (payment) {
            return this.paymentMethods[payment].isEnabled;
        },

        onlyCardEnabled: function () {
            return this.listEnabled.length == 1 && this.paymentMethods.card.isEnabled;
        },

        isSelected: function (payment) {
            return payment == this.currentPayment;
        }
    }
);

new MPHB.HotelDataManager( MPHB._data );

if ( MPHB._data.page.isCheckoutPage ) {
	new MPHB.CheckoutForm( $( '.mphb_sc_checkout-form' ) );
} else if ( MPHB._data.page.isCreateBookingPage ) {
	new MPHB.CheckoutForm( $( '.mphb_cb_checkout_form' ) );
}

if ( MPHB._data.page.isSearchResultsPage ) {
	new MPHB.ReservationCart( $( '.mphb_sc_search_results-wrapper' ) );
}

var calendars = $( '.mphb-calendar.mphb-datepick' );
$.each( calendars, function( index, calendarEl ) {
	new MPHB.RoomTypeCalendar( $( calendarEl ) );
} );

var reservationForms = $( '.mphb-booking-form' );
$.each( reservationForms, function( index, formEl ) {
	new MPHB.ReservationForm( $( formEl ) );
} );

var searchForms = $( 'form.mphb_sc_search-form, form.mphb_widget_search-form, form.mphb_cb_search_form' );
$.each( searchForms, function( index, formEl ) {
	new MPHB.SearchForm( $( formEl ) );
} );

var flexsliderGalleries = $( '.mphb-flexslider-gallery-wrapper' );
$.each( flexsliderGalleries, function( index, flexsliderGallery ) {
	new MPHB.FlexsliderGallery( flexsliderGallery );
} );

var termsAndConditions = $( '.mphb-checkout-terms-wrapper' );
if ( termsAndConditions.length > 0 ) {
	new MPHB.TermsSwitcher( termsAndConditions );
}

// Fix for kbwood/datepick (function show() -> $.ui.version.substring(2))
if ( $.ui == undefined ) {
	$.ui = {};
}
if ( $.ui.version == undefined ) {
	$.ui.version = '1.5-';
}

	} );
})( jQuery );