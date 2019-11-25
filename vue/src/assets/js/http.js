const apiMethods = {
  methods: {
    apiGet(url, data) {
      return new Promise((resolve, reject) => {
        axios.get(url, data).then((response) => {
          resolve(response.data)
        }, (response) => {
          reject(response)
          _g.closeGlobalLoading()
          bus.$message({
            message: '请求超时，请检查网络',
            type: 'warning'
          })
        })
      })
    },
    apiPost(url, data) {
      return new Promise((resolve, reject) => {
        axios.post(url, data).then((response) => {
          resolve(response.data)
        }).catch((response) => {
          console.log('f', response)
          resolve(response)
          bus.$message({
            message: '请求超时，请检查网络',
            type: 'warning'
          })
        })
      })
    },
    apiDelete(url, id) {
      return new Promise((resolve, reject) => {
        axios.delete(url + id).then((response) => {
          resolve(response.data)
        }, (response) => {
          reject(response)
          _g.closeGlobalLoading()
          bus.$message({
            message: '请求超时，请检查网络',
            type: 'warning'
          })
        })
      })
    },
    apiPut(url, id, obj) {
      return new Promise((resolve, reject) => {
        axios.put(url + id, obj).then((response) => {
          resolve(response.data)
        }, (response) => {
          _g.closeGlobalLoading()
          bus.$message({
            message: '请求超时，请检查网络',
            type: 'warning'
          })
          reject(response)
        })
      })
    },
    handelResponse(res, cb, errCb) {
      _g.closeGlobalLoading()
      if (res.code == 200) {
        cb(res.data)
      } else {
        if (typeof errCb == 'function') {
          errCb()
        }
        this.handleError(res)
      }
    },
    handleError(res) {
      if (res.code) {
        switch (res.code) {
          case 1001:
            this.relogin();
            break
          case 1002:
            break
          case 1003:
            this.redirectLoginPage();
            break
          case 1007:
            this.redirectLoginPage();
            break
          default:
            _g.toastMsg('error', res.error || res.message)
        }
      } else {
        console.log('default error')
      }
    },
    relogin() {
      console.log('cookie = ', Cookies.get('rememberPwd'))
      if (!Cookies.get('rememberPwd')) return this.redirectLoginPage()
      let data = {
        rememberKey: Lockr.get('rememberKey')
      }
      this.reAjax('admin/base/relogin', data).then((res) => {
        this.handelResponse(res, (data) => {
          this.resetCommonData(data)
        })
      })
    },
    redirectLoginPage() {
      _g.toastMsg('error', res.error || res.message)
      setTimeout(() => {
        router.replace('/')
      }, 500)
    },
    resetCommonData(data) {
      _(data.menusList).forEach((res, key) => {
        if (key == 0) {
          res.selected = true
        } else {
          res.selected = false
        }
      })
      Lockr.set('menus', data.menusList)              // 菜单数据
      Lockr.set('authKey', data.authKey)              // 权限认证
      Lockr.set('rememberKey', data.rememberKey)      // 记住密码的加密字符串
      Lockr.set('authList', data.authList)            // 权限节点列表
      Lockr.set('userInfo', data.userInfo)            // 用户信息
      Lockr.set('sessionId', data.sessionId)          // 用户sessionid
      window.axios.defaults.headers.authKey = Lockr.get('authKey') || ''
      window.axios.defaults.headers.sessionId = Lockr.get('sessionId') || ''
      let routerUrl = ''
      if (data.menusList[0].url) {
        routerUrl = data.menusList[0].url
      } else {
        routerUrl = data.menusList[0].child[0].child[0].url
      }
      setTimeout(() => {
        let path = this.$route.path
        if (routerUrl != path) {
          router.replace(routerUrl)
        } else {
          _g.shallowRefresh(this.$route.name)
        }
      }, 500)
    },
    reAjax(url, data) {
      return new Promise((resolve, reject) => {
        axios.post(url, data).then((response) => {
          resolve(response.data)
        }, (response) => {
          reject(response)
          bus.$message({
            message: '请求超时，请检查网络',
            type: 'warning'
          })
        })
      })
    }
  },
  computed: {
    showLoading() {
      return store.state.globalLoading
    }
  }
}

export default apiMethods
