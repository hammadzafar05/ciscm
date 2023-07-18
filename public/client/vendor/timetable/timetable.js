var wordMonth = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
var wordDay_sun = 'Sunday';
var wordDay_mon = 'Monday';
var wordDay_tue = 'Tuesday';
var wordDay_wed = 'Wednesday';
var wordDay_thu = 'Thursday';
var wordDay_fri = 'Friday';
var wordDay_sat = 'Saturday';
var tiva_timetables = [];
var tiva_current_date = new Date();
var tiva_current_month = tiva_current_date['getMonth']() + 1;
var tiva_current_year = tiva_current_date['getFullYear']();

function sortByTime(_0x716bxe, _0x716bxf) {
    if (_0x716bxe['start_time'] < _0x716bxf['start_time']) {
        return -1
    } else {
        if (_0x716bxe['start_time'] > _0x716bxf['start_time']) {
            return 1
        } else {
            return 0
        }
    }
}

function sortByEndTime(_0x716bxe, _0x716bxf) {
    if (_0x716bxe['end_time'] < _0x716bxf['end_time']) {
        return -1
    } else {
        if (_0x716bxe['end_time'] > _0x716bxf['end_time']) {
            return 1
        } else {
            return 0
        }
    }
}

function getMinTime(_0x716bx12) {
    for (var _0x716bx13 = 0; _0x716bx13 < _0x716bx12['length']; _0x716bx13++) {
        if (_0x716bx12[_0x716bx13]['start_time']) {
            return parseInt(_0x716bx12[_0x716bx13]['start_time'], 10)
        }
    }
}

function getMaxTime(_0x716bx12) {
    _0x716bx12['sort'](sortByEndTime);
    for (var _0x716bx13 = _0x716bx12['length'] - 1; _0x716bx13 >= 0; _0x716bx13--) {
        if (_0x716bx12[_0x716bx13]['end_time']) {
            var _0x716bx15 = _0x716bx12[_0x716bx13]['end_time']['split'](':');
            if (_0x716bx15[1] == '00') {
                return _0x716bx15[0]
            } else {
                return parseInt(_0x716bx15[0], 10) + 1
            }
        }
    }
}

function calHour(_0x716bx15) {
    var _0x716bx17 = _0x716bx15['split'](':');
    return parseInt(_0x716bx17[0], 10) + (_0x716bx17[1] / 60)
}

function getPosition(_0x716bx19, _0x716bx1a) {
    return parseInt((calHour(_0x716bx1a) - _0x716bx19) * 55, 10)
}

function getHeight(_0x716bx1a, _0x716bx1c) {
    return parseInt((calHour(_0x716bx1c) - calHour(_0x716bx1a)) * 55, 10)
}

function checkMulti(_0x716bx1e, _0x716bx12) {
    var _0x716bx1f = 1;
    for (var _0x716bx13 = 0; _0x716bx13 < _0x716bx12['length']; _0x716bx13++) {
        if (_0x716bx1e['id'] != _0x716bx12[_0x716bx13]['id']) {
            if (((calHour(_0x716bx1e['start_time']) >= calHour(_0x716bx12[_0x716bx13]['start_time'])) && (calHour(_0x716bx1e['start_time']) < calHour(_0x716bx12[_0x716bx13]['end_time']))) || ((calHour(_0x716bx12[_0x716bx13]['start_time']) >= calHour(_0x716bx1e['start_time'])) && (calHour(_0x716bx12[_0x716bx13]['start_time']) < calHour(_0x716bx1e['end_time'])))) {
                _0x716bx1f++
            }
        }
    };
    return _0x716bx1f
}

function checkOrder(_0x716bx1e, _0x716bx12) {
    var _0x716bx21 = 0;
    for (var _0x716bx13 = 0; _0x716bx13 < _0x716bx12['length']; _0x716bx13++) {
        if (_0x716bx1e['id'] > _0x716bx12[_0x716bx13]['id']) {
            if (((calHour(_0x716bx1e['start_time']) >= calHour(_0x716bx12[_0x716bx13]['start_time'])) && (calHour(_0x716bx1e['start_time']) < calHour(_0x716bx12[_0x716bx13]['end_time']))) || ((calHour(_0x716bx12[_0x716bx13]['start_time']) >= calHour(_0x716bx1e['start_time'])) && (calHour(_0x716bx12[_0x716bx13]['start_time']) < calHour(_0x716bx1e['end_time'])))) {
                _0x716bx21++
            }
        }
    };
    return _0x716bx21
}

function getDayBefore(_0x716bx23, _0x716bx24) {
    var _0x716bx25 = new Date();
    return new Date(_0x716bx25['setTime'](_0x716bx23['getTime']() - (_0x716bx24 * 24 * 60 * 60 * 1000)))
}

function getDayAfter(_0x716bx23, _0x716bx24) {
    var _0x716bx25 = new Date();
    return new Date(_0x716bx25['setTime'](_0x716bx23['getTime']() + (_0x716bx24 * 24 * 60 * 60 * 1000)))
}

function naviClick(_0x716bx28, _0x716bx29, _0x716bx2a, _0x716bx2b, _0x716bx2c) {
    createTimetable(jQuery('#' + _0x716bx28), _0x716bx29, _0x716bx2a, _0x716bx2b, _0x716bx2c)
}

function getTimetables(tiva_timetables, _0x716bx23, _0x716bx2e, _0x716bx2f) {
    var _0x716bx12 = [];
    for (var _0x716bx13 = 0; _0x716bx13 < tiva_timetables['length']; _0x716bx13++) {
        if ((tiva_timetables[_0x716bx13]['date'] == _0x716bx23) && (tiva_timetables[_0x716bx13]['month'] == _0x716bx2e) && (tiva_timetables[_0x716bx13]['year'] == _0x716bx2f)) {
            _0x716bx12['push'](tiva_timetables[_0x716bx13])
        }
    };
    return _0x716bx12
}

function getTimetablesDay(tiva_timetables, _0x716bx23) {
    var _0x716bx12 = [];
    for (var _0x716bx13 = 0; _0x716bx13 < tiva_timetables['length']; _0x716bx13++) {
        if (tiva_timetables[_0x716bx13]['day'] == _0x716bx23) {
            _0x716bx12['push'](tiva_timetables[_0x716bx13])
        }
    };
    return _0x716bx12
}

function getTimetablesWeek(tiva_timetables, _0x716bx32, _0x716bx33) {
    var _0x716bx34;
    var _0x716bx12 = [];
    for (var _0x716bx13 = 0; _0x716bx13 < tiva_timetables['length']; _0x716bx13++) {
        _0x716bx34 = new Date(tiva_timetables[_0x716bx13]['year'], Number(tiva_timetables[_0x716bx13]['month']) - 1, tiva_timetables[_0x716bx13]['date']);
        if ((_0x716bx32['getTime']() <= _0x716bx34['getTime']()) && (_0x716bx34['getTime']() <= _0x716bx33['getTime']())) {
            _0x716bx12['push'](tiva_timetables[_0x716bx13])
        }
    };
    return _0x716bx12
}

function createTimetable(_0x716bx36, _0x716bx29, _0x716bx2a, _0x716bx2b, _0x716bx2c) {
    var _0x716bx37;
    var _0x716bx38;
    var _0x716bx39;
    var _0x716bx3a;
    var _0x716bx3b;
    var _0x716bx3c = new Date();
    if (_0x716bx29 == 'prevyr') {
        _0x716bx2c--
    } else {
        if (_0x716bx29 == 'nextyr') {
            _0x716bx2c++
        } else {
            if (_0x716bx29 == 'prevmo') {
                _0x716bx2b--
            } else {
                if (_0x716bx29 == 'nextmo') {
                    _0x716bx2b++
                } else {
                    if (_0x716bx29 == 'prevwe') {
                        _0x716bx3b = new Date(_0x716bx2a);
                        _0x716bx2a = getDayBefore(_0x716bx3b, 7)
                    } else {
                        if (_0x716bx29 == 'nextwe') {
                            _0x716bx3b = new Date(_0x716bx2a);
                            _0x716bx2a = getDayAfter(_0x716bx3b, 7)
                        }
                    }
                }
            }
        }
    };
    if (_0x716bx2b == 0) {
        _0x716bx2b = 12;
        _0x716bx2c--
    } else {
        if (_0x716bx2b == 13) {
            _0x716bx2b = 1;
            _0x716bx2c++
        }
    };
    _0x716bx37 = new Date(_0x716bx2c, _0x716bx2b - 1, 1);
    var _0x716bx3d = (typeof _0x716bx36['attr']('data-start') != 'undefined') ? _0x716bx36['attr']('data-start') : 'sunday';
    if (_0x716bx3d == 'sunday') {
        _0x716bx38 = _0x716bx37['getDay']() + 1
    } else {
        _0x716bx38 = (_0x716bx37['getDay']() == 0) ? 7 : _0x716bx37['getDay']()
    };
    _0x716bx39 = new Date(_0x716bx2c, _0x716bx2b, 0);
    _0x716bx3a = _0x716bx39['getDate']();
    var _0x716bx3e = (typeof _0x716bx36['attr']('data-view') != 'undefined') ? _0x716bx36['attr']('data-view') : 'month';
    if (_0x716bx3e == 'week') {
        timetableWeek(_0x716bx36, tiva_timetables, _0x716bx2a)
    } else {
        if (_0x716bx3e == 'list') {
            timetableList(_0x716bx36, tiva_timetables, _0x716bx2a)
        } else {
            timetableMonth(_0x716bx36, tiva_timetables, _0x716bx38, _0x716bx3a, _0x716bx2b, _0x716bx2c)
        }
    }
}

function timetableWeek(_0x716bx36, tiva_timetables, _0x716bx40) {
    var _0x716bx41 = new Date(_0x716bx40);
    var _0x716bx42 = _0x716bx41['getDate']();
    var _0x716bx43 = _0x716bx41['getMonth']() + 1;
    var _0x716bx44 = _0x716bx41['getFullYear']();
    var _0x716bx45 = getDayAfter(_0x716bx41, 6);
    var _0x716bx46 = _0x716bx45['getDate']();
    var _0x716bx47 = _0x716bx45['getMonth']() + 1;
    var _0x716bx48 = _0x716bx45['getFullYear']();
    var _0x716bx49;
    var _0x716bx25;
    var _0x716bx4a;
    var _0x716bx2e;
    var _0x716bx2f;
    var _0x716bx4b;
    var _0x716bx4c;
    var _0x716bx4d;
    var _0x716bx4e;
    var _0x716bx3d = (typeof _0x716bx36['attr']('data-start') != 'undefined') ? _0x716bx36['attr']('data-start') : 'sunday';
    var _0x716bx4f = '';
    if (_0x716bx3d == 'sunday') {
        _0x716bx4e = new Array(wordDay_sun, wordDay_mon, wordDay_tue, wordDay_wed, wordDay_thu, wordDay_fri, wordDay_sat);
        _0x716bx49 = new Array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')
    } else {
        _0x716bx4e = new Array(wordDay_mon, wordDay_tue, wordDay_wed, wordDay_thu, wordDay_fri, wordDay_sat, wordDay_sun);
        _0x716bx49 = new Array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')
    };
    var _0x716bx50;
    if ((_0x716bx43 != _0x716bx47) && (_0x716bx44 != _0x716bx48)) {
        _0x716bx50 = wordMonth[_0x716bx43 - 1]['substring'](0, 3) + ' ' + _0x716bx42 + ', ' + _0x716bx44 + ' - ' + wordMonth[_0x716bx47 - 1]['substring'](0, 3) + ' ' + _0x716bx46 + ', ' + _0x716bx48
    } else {
        if (_0x716bx43 != _0x716bx47) {
            _0x716bx50 = wordMonth[_0x716bx43 - 1]['substring'](0, 3) + ' ' + _0x716bx42 + ' - ' + wordMonth[_0x716bx47 - 1]['substring'](0, 3) + ' ' + _0x716bx46 + ', ' + _0x716bx44
        } else {
            _0x716bx50 = wordMonth[_0x716bx43 - 1]['substring'](0, 3) + ' ' + _0x716bx42 + ' - ' + _0x716bx46 + ', ' + _0x716bx44
        }
    };
    if (!(_0x716bx36['attr']('data-nav') == 'hide') && !(_0x716bx36['attr']('data-mode') == 'day')) {
        _0x716bx4f += '<div class="time-navigation">' + '<span class="navi-icon navi-prev" onClick="naviClick(\'' + _0x716bx36['attr']('id') + '\', \'prevwe\', \'' + _0x716bx41 + '\', ' + _0x716bx43 + ', ' + _0x716bx44 + ')">&#10094;</span>' + '<span class="navi-time">' + _0x716bx50 + '</span>' + '<span class="navi-icon navi-next" onClick="naviClick(\'' + _0x716bx36['attr']('id') + '\', \'nextwe\', \'' + _0x716bx41 + '\', ' + _0x716bx43 + ', ' + _0x716bx44 + ')">&#10095;</span>' + '</div>'
    };
    if (!(_0x716bx36['attr']('data-mode') == 'day')) {
        tiva_timetables = getTimetablesWeek(tiva_timetables, new Date(_0x716bx44, _0x716bx43 - 1, _0x716bx42), new Date(_0x716bx48, _0x716bx47 - 1, _0x716bx46))
    };
    var _0x716bx51 = getMinTime(tiva_timetables) ? getMinTime(tiva_timetables) : 8;
    var _0x716bx52 = getMaxTime(tiva_timetables) ? getMaxTime(tiva_timetables) : 15;
    var _0x716bx53 = (_0x716bx36['attr']('data-header-time') == 'hide') ? '' : 'show-time';
    _0x716bx4f += '<div class="timetable-week ' + _0x716bx53 + '">';
    _0x716bx4f += '<div class="timetable-axis">';
    for (var _0x716bx54 = _0x716bx51; _0x716bx54 <= _0x716bx52; _0x716bx54++) {
        var _0x716bx55 = (_0x716bx54 < 10) ? '0' + _0x716bx54 : _0x716bx54;
        _0x716bx4f += '<div class="axis-item">' + _0x716bx55 + ':00</div>'
    };
    _0x716bx4f += '</div>';
    _0x716bx4f += '<div class="timetable-columns">';
    for (var _0x716bx56 = 0; _0x716bx56 < _0x716bx4e['length']; _0x716bx56++) {
        _0x716bx25 = getDayAfter(_0x716bx41, _0x716bx56);
        _0x716bx4a = _0x716bx25['getDate']();
        _0x716bx2e = _0x716bx25['getMonth']() + 1;
        _0x716bx2f = _0x716bx25['getFullYear']();
        _0x716bx4d = (_0x716bx36['attr']('data-header-time') == 'hide') ? '' : '<br><span>' + wordMonth[_0x716bx2e - 1]['substring'](0, 3) + ' ' + _0x716bx4a + ', ' + _0x716bx2f + '</span>';
        _0x716bx4b = (_0x716bx56 == 0) ? 'first-column' : '';
        _0x716bx4c = (_0x716bx56 == _0x716bx4e['length'] - 1) ? 'last-column' : '';
        _0x716bx4f += '<div class="timetable-column">';
        if (screen['width'] > 768) {
            _0x716bx4f += '<div class="timetable-column-header ' + _0x716bx4c + '">' + _0x716bx4e[_0x716bx56] + _0x716bx4d + '</div>'
        } else {
            _0x716bx4f += '<div class="timetable-column-header ' + _0x716bx4c + '">' + _0x716bx4e[_0x716bx56]['substring'](0, 3) + _0x716bx4d + '</div>'
        };
        _0x716bx4f += '<div class="timetable-column-content">';
        if (_0x716bx36['attr']('data-mode') == 'day') {
            var _0x716bx12 = getTimetablesDay(tiva_timetables, _0x716bx49[_0x716bx56])
        } else {
            var _0x716bx12 = getTimetables(tiva_timetables, _0x716bx4a, _0x716bx2e, _0x716bx2f)
        };
        for (var _0x716bx17 = 0; _0x716bx17 < _0x716bx12['length']; _0x716bx17++) {
            if (_0x716bx12[_0x716bx17]['start_time'] && _0x716bx12[_0x716bx17]['end_time']) {
                if (_0x716bx12[_0x716bx17]['image']) {
                    var _0x716bx57 = '<div class="timetable-image"><img src="' + _0x716bx12[_0x716bx17]['image'] + '" alt="' + _0x716bx12[_0x716bx17]['name'] + '" /></div>'
                } else {
                    var _0x716bx57 = ''
                };
                if (_0x716bx12[_0x716bx17]['end_time']) {
                    var _0x716bx58 = ' - ' + _0x716bx12[_0x716bx17]['end_time']
                } else {
                    var _0x716bx58 = ''
                };
                var _0x716bx59 = getPosition(_0x716bx51, _0x716bx12[_0x716bx17]['start_time']);
                var _0x716bx5a = getHeight(_0x716bx12[_0x716bx17]['start_time'], _0x716bx12[_0x716bx17]['end_time']);
                var _0x716bx5b = (checkMulti(_0x716bx12[_0x716bx17], _0x716bx12) > 1) ? 'width:' + (100 / checkMulti(_0x716bx12[_0x716bx17], _0x716bx12)) + '%;' : '';
                var _0x716bx5c = (checkOrder(_0x716bx12[_0x716bx17], _0x716bx12) > 0) ? 'left:' + (checkOrder(_0x716bx12[_0x716bx17], _0x716bx12) * (100 / checkMulti(_0x716bx12[_0x716bx17], _0x716bx12))) + '%' : '';
                _0x716bx4f += '<div class="timetable-item">' + '<a class="timetable-title color-' + _0x716bx12[_0x716bx17]['color'] + '" style="top:' + _0x716bx59 + 'px; height:' + _0x716bx5a + 'px; ' + _0x716bx5b + _0x716bx5c + '" href="#timetable-popup-' + _0x716bx12[_0x716bx17]['id'] + '" class="open-popup-link">' + '<div class="timetable-title-wrap">' + '<div class="timetable-name">' + _0x716bx12[_0x716bx17]['name'] + '</div>' + '<div class="timetable-time">' + _0x716bx12[_0x716bx17]['start_time'] + ' - ' + _0x716bx12[_0x716bx17]['end_time'] + '</div>' + '</div>' + '</a>' + '<div id="timetable-popup-' + _0x716bx12[_0x716bx17]['id'] + '" class="timetable-popup zoom-anim-dialog mfp-hide">' + '<div class="popup-header color-' + _0x716bx12[_0x716bx17]['color'] + '">' + _0x716bx12[_0x716bx17]['name'] + '</div>' + '<div class="popup-body">' + _0x716bx57 + '<div class="timetable-time color-' + _0x716bx12[_0x716bx17]['color'] + '">' + _0x716bx12[_0x716bx17]['start_time'] + _0x716bx58 + '</div>' + '<div class="timetable-desc">' + _0x716bx12[_0x716bx17]['description'] + '</div>' + '</div>' + '</div>' + '</div>'
            }
        };
        _0x716bx4f += '</div>';
        _0x716bx4f += '<div class="timetable-column-grid">';
        for (var _0x716bx54 = _0x716bx51; _0x716bx54 < _0x716bx52; _0x716bx54++) {
            _0x716bx4f += '<div class="grid-item ' + _0x716bx4b + '"></div>'
        };
        _0x716bx4f += '</div>';
        _0x716bx4f += '</div>'
    };
    _0x716bx4f += '</div>';
    _0x716bx4f += '</div>';
    _0x716bx36['html'](_0x716bx4f);
    _0x716bx36['find']('.timetable-title')['magnificPopup']({
        type: 'inline',
        removalDelay: 800,
        mainClass: 'my-mfp-zoom-in'
    })
}

function timetableList(_0x716bx36, tiva_timetables, _0x716bx40) {
    var _0x716bx41 = new Date(_0x716bx40);
    var _0x716bx42 = _0x716bx41['getDate']();
    var _0x716bx43 = _0x716bx41['getMonth']() + 1;
    var _0x716bx44 = _0x716bx41['getFullYear']();
    var _0x716bx45 = getDayAfter(_0x716bx41, 6);
    var _0x716bx46 = _0x716bx45['getDate']();
    var _0x716bx47 = _0x716bx45['getMonth']() + 1;
    var _0x716bx48 = _0x716bx45['getFullYear']();
    var _0x716bx49;
    var _0x716bx25;
    var _0x716bx4a;
    var _0x716bx2e;
    var _0x716bx2f;
    var _0x716bx4b;
    var _0x716bx4c;
    var _0x716bx4d;
    var _0x716bx4e;
    var _0x716bx3d = (typeof _0x716bx36['attr']('data-start') != 'undefined') ? _0x716bx36['attr']('data-start') : 'sunday';
    var _0x716bx4f = '';
    if (_0x716bx3d == 'sunday') {
        _0x716bx4e = new Array(wordDay_sun, wordDay_mon, wordDay_tue, wordDay_wed, wordDay_thu, wordDay_fri, wordDay_sat);
        _0x716bx49 = new Array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')
    } else {
        _0x716bx4e = new Array(wordDay_mon, wordDay_tue, wordDay_wed, wordDay_thu, wordDay_fri, wordDay_sat, wordDay_sun);
        _0x716bx49 = new Array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')
    };
    var _0x716bx50;
    if ((_0x716bx43 != _0x716bx47) && (_0x716bx44 != _0x716bx48)) {
        _0x716bx50 = wordMonth[_0x716bx43 - 1]['substring'](0, 3) + ' ' + _0x716bx42 + ', ' + _0x716bx44 + ' - ' + wordMonth[_0x716bx47 - 1]['substring'](0, 3) + ' ' + _0x716bx46 + ', ' + _0x716bx48
    } else {
        if (_0x716bx43 != _0x716bx47) {
            _0x716bx50 = wordMonth[_0x716bx43 - 1]['substring'](0, 3) + ' ' + _0x716bx42 + ' - ' + wordMonth[_0x716bx47 - 1]['substring'](0, 3) + ' ' + _0x716bx46 + ', ' + _0x716bx44
        } else {
            _0x716bx50 = wordMonth[_0x716bx43 - 1]['substring'](0, 3) + ' ' + _0x716bx42 + ' - ' + _0x716bx46 + ', ' + _0x716bx44
        }
    };
    if (!(_0x716bx36['attr']('data-nav') == 'hide') && !(_0x716bx36['attr']('data-mode') == 'day')) {
        _0x716bx4f += '<div class="time-navigation">' + '<span class="navi-icon navi-prev" onClick="naviClick(\'' + _0x716bx36['attr']('id') + '\', \'prevwe\', \'' + _0x716bx41 + '\', ' + _0x716bx43 + ', ' + _0x716bx44 + ')">&#10094;</span>' + '<span class="navi-time">' + _0x716bx50 + '</span>' + '<span class="navi-icon navi-next" onClick="naviClick(\'' + _0x716bx36['attr']('id') + '\', \'nextwe\', \'' + _0x716bx41 + '\', ' + _0x716bx43 + ', ' + _0x716bx44 + ')">&#10095;</span>' + '</div>'
    };
    var _0x716bx12;
    _0x716bx4f += '<div class="timetable-list">';
    for (var _0x716bx56 = 0; _0x716bx56 < _0x716bx4e['length']; _0x716bx56++) {
        _0x716bx25 = getDayAfter(_0x716bx41, _0x716bx56);
        _0x716bx4a = _0x716bx25['getDate']();
        _0x716bx2e = _0x716bx25['getMonth']() + 1;
        _0x716bx2f = _0x716bx25['getFullYear']();
        if (_0x716bx36['attr']('data-mode') == 'day') {
            _0x716bx12 = getTimetablesDay(tiva_timetables, _0x716bx49[_0x716bx56])
        } else {
            _0x716bx12 = getTimetables(tiva_timetables, _0x716bx4a, _0x716bx2e, _0x716bx2f)
        };
        if (_0x716bx12['length'] > 0) {
            _0x716bx4f += '<div class="timetable-day">';
            _0x716bx4d = (_0x716bx36['attr']('data-header-time') == 'hide') ? '' : '<span>' + wordMonth[_0x716bx2e - 1]['substring'](0, 3) + ' ' + _0x716bx4a + ', ' + _0x716bx2f + '</span>';
            _0x716bx4f += '<div class="timetable-header">' + _0x716bx4e[_0x716bx56] + _0x716bx4d + '</div>';
            _0x716bx4f += '<div class="timetable-content">';
            for (var _0x716bx17 = 0; _0x716bx17 < _0x716bx12['length']; _0x716bx17++) {
                if (_0x716bx12[_0x716bx17]['start_time'] && _0x716bx12[_0x716bx17]['end_time']) {
                    if (_0x716bx12[_0x716bx17]['image']) {
                        var _0x716bx57 = '<div class="timetable-image"><img src="' + _0x716bx12[_0x716bx17]['image'] + '" alt="' + _0x716bx12[_0x716bx17]['name'] + '" /></div>'
                    } else {
                        var _0x716bx57 = ''
                    };
                    if (_0x716bx12[_0x716bx17]['end_time']) {
                        var _0x716bx58 = ' - ' + _0x716bx12[_0x716bx17]['end_time']
                    } else {
                        var _0x716bx58 = ''
                    };
                    _0x716bx4f += '<div class="timetable-item">' + '<span class="timetable-color color-' + _0x716bx12[_0x716bx17]['color'] + '"></span>' + '<a class="timetable-title" href="#timetable-popup-' + _0x716bx12[_0x716bx17]['id'] + '" class="open-popup-link">' + '<span class="timetable-time">' + _0x716bx12[_0x716bx17]['start_time'] + _0x716bx58 + '</span>' + '<span class="timetable-name">' + _0x716bx12[_0x716bx17]['name'] + '</span>' + '</a>' + '<div id="timetable-popup-' + _0x716bx12[_0x716bx17]['id'] + '" class="timetable-popup zoom-anim-dialog mfp-hide">' + '<div class="popup-header color-' + _0x716bx12[_0x716bx17]['color'] + '">' + _0x716bx12[_0x716bx17]['name'] + '</div>' + '<div class="popup-body">' + _0x716bx57 + '<div class="timetable-time color-' + _0x716bx12[_0x716bx17]['color'] + '">' + _0x716bx12[_0x716bx17]['start_time'] + _0x716bx58 + '</div>' + '<div class="timetable-desc">' + _0x716bx12[_0x716bx17]['description'] + '</div>' + '</div>' + '</div>' + '</div>'
                }
            };
            _0x716bx4f += '</div>';
            _0x716bx4f += '</div>'
        }
    };
    _0x716bx4f += '</div>';
    _0x716bx36['html'](_0x716bx4f);
    _0x716bx36['find']('.timetable-title')['magnificPopup']({
        type: 'inline',
        removalDelay: 800,
        mainClass: 'my-mfp-zoom-in'
    })
}

function timetableMonth(_0x716bx36, tiva_timetables, _0x716bx38, _0x716bx3a, _0x716bx2b, _0x716bx2c) {
    var _0x716bx5f = tiva_current_date['getDate']();
    var _0x716bx4e;
    var _0x716bx3d = (typeof _0x716bx36['attr']('data-start') != 'undefined') ? _0x716bx36['attr']('data-start') : 'sunday';
    var _0x716bx4f = '';
    var _0x716bx60 = 0;
    if (_0x716bx3d == 'sunday') {
        _0x716bx4e = new Array(wordDay_sun, wordDay_mon, wordDay_tue, wordDay_wed, wordDay_thu, wordDay_fri, wordDay_sat)
    } else {
        _0x716bx4e = new Array(wordDay_mon, wordDay_tue, wordDay_wed, wordDay_thu, wordDay_fri, wordDay_sat, wordDay_sun)
    };
    if (!(_0x716bx36['attr']('data-nav') == 'hide')) {
        _0x716bx4f += '<div class="time-navigation">' + '<span class="navi-icon navi-prev" onClick="naviClick(\'' + _0x716bx36['attr']('id') + '\', \'prevmo\', \'\', ' + _0x716bx2b + ', ' + _0x716bx2c + ')">&#10094;</span>' + '<span class="navi-time">' + wordMonth[_0x716bx2b - 1] + '&nbsp;&nbsp;' + _0x716bx2c + '</span>' + '<span class="navi-icon navi-next" onClick="naviClick(\'' + _0x716bx36['attr']('id') + '\', \'nextmo\', \'\', ' + _0x716bx2b + ', ' + _0x716bx2c + ')">&#10095;</span>' + '</div>'
    };
    _0x716bx4f += '<table class="timetable-month">';
    _0x716bx4f += '<tbody>';
    _0x716bx4f += '<tr>';
    for (var _0x716bx56 = 0; _0x716bx56 < _0x716bx4e['length']; _0x716bx56++) {
        if (screen['width'] > 768) {
            _0x716bx4f += '<th class="timetable-header">' + _0x716bx4e[_0x716bx56] + '</th>'
        } else {
            _0x716bx4f += '<th class="timetable-header">' + _0x716bx4e[_0x716bx56]['substring'](0, 3) + '</th>'
        }
    };
    _0x716bx4f += '</tr>';
    var _0x716bx61 = 1;
    var _0x716bx62;
    for (var _0x716bx13 = 1; _0x716bx13 <= 6; _0x716bx13++) {
        var _0x716bx63 = (_0x716bx13 - 1) * 7 + 1;
        if (_0x716bx63 < (_0x716bx38 + _0x716bx3a)) {
            _0x716bx4f += '<tr>';
            for (var _0x716bx64 = 1; _0x716bx64 <= 7; _0x716bx64++) {
                _0x716bx60 = (_0x716bx61 - _0x716bx38) + 1;
                _0x716bx61++;
                _0x716bx62 = ((_0x716bx5f == _0x716bx60) && (tiva_current_month == _0x716bx2b) && (tiva_current_year == _0x716bx2c)) ? 'today' : '';
                _0x716bx4f += '<td class="calendar-day ' + _0x716bx62 + '">';
                if ((_0x716bx60 <= _0x716bx3a) && (_0x716bx60 >= 1)) {
                    _0x716bx4f += '<div class="calendar-daycounter">' + _0x716bx60 + '</div>'
                };
                var _0x716bx12 = getTimetables(tiva_timetables, _0x716bx60, _0x716bx2b, _0x716bx2c);
                for (var _0x716bx17 = 0; _0x716bx17 < _0x716bx12['length']; _0x716bx17++) {
                    if (_0x716bx12[_0x716bx17]['image']) {
                        var _0x716bx57 = '<div class="timetable-image"><img src="' + _0x716bx12[_0x716bx17]['image'] + '" alt="' + _0x716bx12[_0x716bx17]['name'] + '" /></div>'
                    } else {
                        var _0x716bx57 = ''
                    };
                    if (_0x716bx12[_0x716bx17]['end_time']) {
                        var _0x716bx58 = ' - ' + _0x716bx12[_0x716bx17]['end_time']
                    } else {
                        var _0x716bx58 = ''
                    };
                    _0x716bx4f += '<div class="timetable-item">' + '<span class="timetable-color color-' + _0x716bx12[_0x716bx17]['color'] + '"></span>' + '<a class="timetable-title" href="#timetable-popup-' + _0x716bx12[_0x716bx17]['id'] + '" class="open-popup-link">' + '<span class="timetable-time">' + _0x716bx12[_0x716bx17]['start_time'] + '</span>' + '<span class="timetable-name">' + _0x716bx12[_0x716bx17]['name'] + '</span>' + '</a>' + '<div id="timetable-popup-' + _0x716bx12[_0x716bx17]['id'] + '" class="timetable-popup zoom-anim-dialog mfp-hide">' + '<div class="popup-header color-' + _0x716bx12[_0x716bx17]['color'] + '">' + _0x716bx12[_0x716bx17]['name'] + '</div>' + '<div class="popup-body">' + _0x716bx57 + '<div class="timetable-time color-' + _0x716bx12[_0x716bx17]['color'] + '">' + _0x716bx12[_0x716bx17]['start_time'] + _0x716bx58 + '</div>' + '<div class="timetable-desc">' + _0x716bx12[_0x716bx17]['description'] + '</div>' + '</div>' + '</div>' + '</div>'
                };
                _0x716bx4f += '</td>'
            };
            _0x716bx4f += '</tr>'
        }
    };
    _0x716bx4f += '</tbody>';
    _0x716bx4f += '</table>';
    _0x716bx36['html'](_0x716bx4f);
    _0x716bx36['find']('.timetable-title')['magnificPopup']({
        type: 'inline',
        removalDelay: 800,
        mainClass: 'my-mfp-zoom-in'
    });
    _0x716bx61 = 1
}
jQuery(document)['ready'](function() {
    jQuery('.tiva-timetable')['each'](function(_0x716bx65) {
        jQuery(this)['attr']('id', 'timetable-' + (_0x716bx65 + 1));
        var _0x716bx3e = (typeof jQuery(this)['attr']('data-view') != 'undefined') ? jQuery(this)['attr']('data-view') : 'month';
        if ((jQuery(this)['attr']('data-mode') == 'day') && ((_0x716bx3e == 'week') || (_0x716bx3e == 'list'))) {
            var _0x716bx66 = 'day'
        } else {
            var _0x716bx66 = 'date'
        };
        var _0x716bx67 = jQuery(this);
        jQuery['ajax']({
            url: './timetables/tiva2',
            dataType: 'json',
            data: '{mode:mode}',
            beforeSend: function() {
                _0x716bx67['html']('<div class="loading"><img src="./img/loading-tiva.gif" /></div>')
            },
            success: function(_0x716bx68) {
                //alert('asdas');
                tiva_timetables = [];
                for (var _0x716bx13 = 0; _0x716bx13 < _0x716bx68['length']; _0x716bx13++) {
                    tiva_timetables['push'](_0x716bx68[_0x716bx13])
                };
                tiva_timetables['sort'](sortByTime);
                for (var _0x716bx69 = 0; _0x716bx69 < tiva_timetables['length']; _0x716bx69++) {
                    tiva_timetables[_0x716bx69]['id'] = _0x716bx69
                };
                var _0x716bx3c = new Date();
                var _0x716bx3d = (typeof _0x716bx67['attr']('data-start') != 'undefined') ? _0x716bx67['attr']('data-start') : 'sunday';
                if (_0x716bx3d == 'sunday') {
                    var _0x716bx6a = new Date(_0x716bx3c['setDate'](tiva_current_date['getDate']() - _0x716bx3c['getDay']()))
                } else {
                    var _0x716bx6b = (_0x716bx3c['getDay']() == 0) ? 7 : _0x716bx3c['getDay']();
                    var _0x716bx6a = new Date(_0x716bx3c['setDate'](tiva_current_date['getDate']() - _0x716bx6b + 1))
                };
                createTimetable(_0x716bx67, 'current', _0x716bx6a, tiva_current_month, tiva_current_year)
            }
        })
    })
})