import lines from "./Lines"
class Lines {
    getLineWeek(line) {
        return lines[line]
    }
    getAllLines(){
        return lines
    }
}

export default new Lines;