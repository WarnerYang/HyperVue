const commonFn = {
  j2s(obj) {
    return JSON.stringify(obj)
  },
  shallowRefresh(name, query) {
    router.replace({ path: '/refresh', query: { name: name, query: query } })
  },
  closeGlobalLoading() {
    setTimeout(() => {
      store.dispatch('showLoading', false)
    })
  },
  openGlobalLoading() {
    setTimeout(() => {
      store.dispatch('showLoading', true)
    })
  },
  cloneJson(obj) {
    return JSON.parse(JSON.stringify(obj))
  },
  toastMsg(type, msg) {
    switch (type) {
      case 'normal':
        bus.$message(msg)
        break
      case 'success':
        bus.$message({
          message: msg,
          type: 'success'
        })
        break
      case 'warning':
        bus.$message({
          message: msg,
          type: 'warning'
        })
        break
      case 'error':
        bus.$message.error(msg)
        break
    }
  },
  clearVuex(cate) {
    store.dispatch(cate, [])
  },
  getHasRule(val) {
    const userInfo = Lockr.get('userInfo')
    if (userInfo.id == 1) {
      return true
    } else {
      const authList = Lockr.get('authList')
      return _.includes(authList, val)
    }
  }
}

export default commonFn
