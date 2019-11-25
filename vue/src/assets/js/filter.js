import Vue from 'vue'
export default (function () {
    Vue.filter('status', function (value) {
        if (value == 1) {
            return '启用'
        } else if (value == 0) {
            return '禁用'
        } else {
            return '未知状态'
        }
    })
    Vue.filter('tagType', function (value) {
        if (value == 1) {
            return 'success'
        } else if (value == 0) {
            return 'danger'
        } else {
            return ''
        }
    })
    Vue.filter('rules', function (value) {
        return value
    })
    Vue.filter('fileLink', function (value) {
        const link = window.imgUrl + value
        return link
    })
    Vue.filter('toolType', function (value) {
        let type = ''
        if (value == 1) {
            type = '系统工具'
        } else if (value == 2) {
            type = '说明指导'
        }
        return type
    })
    Vue.filter('numToString', function (value) {
        const string = value.toString()
        return string
    })
    Vue.filter('projectState', function (value) {
        let string = ''
        switch (value) {
            case '1':
                string = '售前项目'
                break
            case '2':
                string = '服务中项目'
                break
            case '3':
                string = '已结束项目'
                break
        }
        return string
    })
    Vue.filter('time', function (value) {
        let day = moment.unix(value)
        let date = moment(day).format('YYYY/MM/DD H:mm')
        return date
    })
    Vue.filter('date', function (value) {
        let day = moment.unix(value)
        let date = moment(day).format('YYYY/MM/DD')
        return date
    })
    Vue.filter('abstract', function (value) {
        let abstract = ''
        if (value.length > 70) {
            abstract = value.substr(0, 70) + '...'
        } else {
            abstract = value
        }
        return abstract
    })
    Vue.filter('posStatus', function (value) {
        let status = ''
        switch (value) {
            case 1:
                status = '在职'
                break
            case 2:
                status = '待入职'
                break
            case 3:
                status = '离职'
                break
        }
        return status
    })
    Vue.filter('template', function (value) {
        let template = ''
        if (value == '') {
            template = '上传'
        } else {
            template = '上传更新'
        }
        return template
    })
    Vue.filter('menuType', function (value) {
        let title = ''
        switch (value) {
            case 1:
                title = '普通菜单'
                break
            case 2:
                title = '外链'
                break
        }
        return title
    })
    Vue.filter('ruleLevel', function (value) {
        let title = ''
        switch (value) {
            case 1:
                title = '模块'
                break
            case 2:
                title = '控制器'
                break
            case 3:
                title = '操作'
                break
        }
        return title
    })
})()