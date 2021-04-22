var filter = () => {
    let dateTimeFormatter = (t, fmt = "yy/MM/dd") => {
      let d = new Date(t);
      if (d.toJSON()) {
        return new Intl.DateTimeFormat('en-US').format(d)
      } else {
        return t;
      }
    }
    return {
      filters: {
        dateTimeFormatter,
      },
    }
  }
  export default filter()
  