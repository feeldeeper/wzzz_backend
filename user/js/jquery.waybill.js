/// Author:小虾虎鱼, Date:2014-2-16, 路单
/// www.xiaoboy.com
/// 存在已知的bug：多条长路聚集在一块的时候会存在显示异常
$.fn.Waybill = function (option) {
    var cache = $(this).data('cache'),
        defaults = {
            isToradora: false, // 是否是龙虎斗（即是否显示5条路）
            url:'' // 获取数据的URL
        };
    var option = $.extend(defaults, option);

    var WayBill = function () {
        var c = {};
        this.C = c;
        this.init = function () {
            this.originData = this.HandleUpRoadData();
            if (option.isToradora) {
                this.colsArr = this.TotalCol();
                return this.BigRoad()
                    + '<div class="waybill-1">'
                    + '<div class="next-three-road">'
                    + this.BigEyeRoad() + this.SmallRoad() + this.RoachRoad()
                    + '</div>'
                    + this.PearlRoad()
                    + '</div>';
            } else {
                return this.BigRoad() + this.PearlRoad();
            }
        }
    }

    /// 生成表格
    WayBill.prototype.RenderTable = function (data, option) {
        var that = this, x = option.x || 50, y = option.y || 6, str = '';
        for (var i = 1; i <= y; i++) {
            str += '<tr>';
            for (var j = 1; j <= x; j++) {
                var d = data[j + '_' + i], k = t = '';
                if (/^\d+$/.test(d)) {
                    t = d;
                } else {
                    k = 'class="' + d + '"';
                }

                var s = d ? '<i ' + k + '>' + t + '</i>' : '';
                str += '<td xy="' + j + ',' + i + '">' + s + '</td>';
            }
            str += '</tr>';
        }
        return '<table border="0" cellpadding="0" cellspacing="1" class="waybill-table ' + option.klass + '-table">' + str + '</table>';
    }

    /// 数据的处理
    WayBill.prototype.HandleUpRoadData = function () {
		/// 在此处补充ajax
        var testData = ['龙', '龙', '龙', '龙', '龙', '虎', '虎', '虎', '虎', '龙', '龙', '虎', '虎', '龙', '虎', '虎', '龙', '龙', '龙', '龙', '龙', '龙', '龙', '龙', '虎', '虎', '虎', '虎', '龙', '龙', '龙', '龙', '龙', '龙', '虎', '龙', '龙', '龙', '虎', '龙', '虎', '龙', '龙', '龙', '龙', '虎', '虎', '龙', '虎', '虎', '虎', '龙', '龙', '虎', '龙', '虎', '龙', '虎', '虎', '龙', '龙', '虎'];

        return testData;
    }

    /// 生成 路单 的走势 数据对象 (适用于 大路、大眼仔、小路、蟑螂路)
    WayBill.prototype.Trend = function (arr, option, originOps) {
        var that = this, dataObj = {},
            dataObj2 = {},// 存储原始坐标
            originOps = originOps || false, // 是否返回原始坐标对象
            red = option.red || '',
            blue = option.blue || '',
            data, reg = '', arr1 = arr.concat();

        $.each($.unique(arr1), function (a, b) {
            reg += '(' + b + ',)+|';
        });
        reg = new RegExp(reg.slice(0, -1), 'g');
        data = (arr.join(',') + ',').match(reg);

        // a对应着x轴，m对应y轴（假设有无限行）
        // 将路单信息存储到dataObj对象中，对象的属性名为相应的坐标
        // 1对应red, 2对应blue
        arr.length > 0 && $.each(data, function (a, b) {
            var p = b.slice(0, -1).split(','), long = false, x0 = y0 = null;
            $.each(p, function (m, n) {
                var x = a + 1, y = m + 1, r;
                if (/单|大|龙/.test(n)) {
                    n = red == '' ? 'icon-red-1' : red;
                } else if (/双|小|虎/.test(n)) {
                    n = blue == '' ? 'icon-blue-1' : blue;
                }

                dataObj2[x + '_' + y] = n;
                if (((dataObj[x + '_' + y] && y < 7) || y == 7) && !long) {// 开始转弯
                    long = true;
                    //  转弯后的坐标
                    x0 = x + 1;
                    y0 = y - 1;
                    dataObj[x0 + '_' + y0] = n;
                } else if (long) {
                    dataObj[(x + y - y0) + '_' + y0] = n;
                } else {
                    if (y > 6) {
                        x = x + y - 6;
                        y = 6;
                    }
                    // 检查该粒的(垂直方向)下一粒 与 该粒的(水平方向)左边粒子 是否同色
                    if (dataObj[x + '_' + (y + 1)] == n || dataObj[(x - 1) + '_' + y] == n) {
                        dataObj[(x + 1) + '_' + (y - 1)] = n;
                    } else {
                        dataObj[x + '_' + y] = n;
                    }
                }
            });
        });
        return originOps ? { newPos: dataObj, oldPos: dataObj2 } : dataObj;
    }

    /// 大路
    WayBill.prototype.BigRoad = function () {
        var c = this.C, dataObj = this.Trend(this.originData, { red: 'icon-red-1', blue: 'icon-blue-1' }, 1);
        this.bigRoadData = dataObj;
        return '<div class="big-road">' + this.RenderTable(dataObj.newPos, { klass: 'big-road' }) + '</div>';
    }

    /// 珠盘路
    WayBill.prototype.PearlRoad = function () {
        var c = this.C, arr = this.originData, dataObj = {};
        $.each(arr, function (a, n) {
            var x = parseInt(a / 6) + 1, y = a % 6 + 1;
            if (/单/.test(n)) {
                n = 'icon-odd';
            } else if (/双/.test(n)) {
                n = 'icon-even';
            } else if (/大/.test(n)) {
                n = 'icon-big';
            } else if (/小/.test(n)) {
                n = 'icon-small';
            } else if (/龙/.test(n)) {
                n = 'icon-dragon';
            } else if (/虎/.test(n)) {
                n = 'icon-tiger';
            }
            dataObj[x + '_' + y] = n;
        });
        var isToradora = option.isToradora;
        return '<div class="pearl-road ' + (isToradora && 'pearl-road-5') + '">' + this.RenderTable(dataObj, { klass: 'pearl-road', x: (isToradora ? 16 : 31) }) + '</div>';
    }

    /// 统计每列的个数，供齐整使用
    WayBill.prototype.TotalCol = function () {
        var that = this, colsArr = [], reg = '',
            arr = that.originData.concat(),
            arr1 = that.originData.concat();
        $.each($.unique(arr1), function (a, b) {
            reg += '(' + b + ',)+|';
        });
        reg = new RegExp(reg.slice(0, -1), 'g');
        data = (arr.join(',') + ',').match(reg);

        $.each(data, function (a, b) {
            colsArr.push(b.slice(0, -1).split(',').length);
        });
		
        return colsArr;
    }

    /// 从大路中 整理出 下三路 的数据
    WayBill.prototype.DownRoad = function (startX) {
        var that = this, c = this.C, startX = 2, dataObj = {},
            bigRoadData = this.bigRoadData.oldPos,
            colsArr = this.colsArr,
            newArr = [];
		//alert(JSON.stringify(bigRoadData));
        /// 判断 下路中的三条路分别是否有数据,无数据则返回空数组
        if ((startX == 2 && !(bigRoadData['2_2'] || bigRoadData['3_1']))
            ||
            (startX == 3 && !(bigRoadData['3_2'] || bigRoadData['4_1']))
            ||
            (startX == 4 && !(bigRoadData['4_2'] || bigRoadData['5_1']))
            ) {
            return newArr;
        }
        $.each(bigRoadData, function (a, b) {
            var d = a.split('_'), xy = { x: d[0], y: d[1] };
            if (xy.x > startX || (xy.x == startX && xy.y > 1)) {
                /// 看齐整
                if (xy.y == 1) {
                    if (colsArr[xy.x - 2] == colsArr[xy.x - 1 - startX]) {
                        newArr.push('龙');
                    } else {
                        newArr.push('虎');
                    }
                }
                    /// 看有无
                else {
                    var x = xy.x - startX + 1;
                    if (bigRoadData[x + '_' + xy.y]) {
                        newArr.push('龙');
                    }
                        /// 看直落
                    else {
                        if (xy.y == 2) {
                            newArr.push('虎');
                        }
                            /// 为直落
                        else if (!bigRoadData[(xy.x - startX + 1) + '_' + (xy.y - 1)]) {
                            newArr.push('龙');
                        } else {
                            newArr.push('虎');
                        }
                    }
                }
            }
        });
        return newArr;
    }

    /// 大眼仔
    WayBill.prototype.BigEyeRoad = function () {
        var dataObj = this.Trend(this.DownRoad(2), { red: 'icon-red-3', blue: 'icon-blue-3' });
        return '<div class="big-road">' + this.RenderTable(dataObj, { klass: 'big-eye-road', x: 44 }) + '</div>';
    }

    /// 小路
    WayBill.prototype.SmallRoad = function () {
        var dataObj = this.Trend(this.DownRoad(3), { red: 'icon-red-4', blue: 'icon-blue-4' });
        return '<div class="small-road">' + this.RenderTable(dataObj, { klass: 'small-road', x: 44 }) + '</div>';
    }

    /// 蟑螂路
    WayBill.prototype.RoachRoad = function () {
        var dataObj = this.Trend(this.DownRoad(4), { red: 'icon-red-5', blue: 'icon-blue-5' });
        return '<div class="roach-road">' + this.RenderTable(dataObj, { klass: 'roach-road', x: 44 }) + '</div>';
    }

    var str = new WayBill().init();
    $(this).html(str);
}