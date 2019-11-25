const formMixin = {
  methods: {
    goback() {
      router.go(-1)
    },
    go(num) {
      router.go(num)
    }
  }
}

export default formMixin
