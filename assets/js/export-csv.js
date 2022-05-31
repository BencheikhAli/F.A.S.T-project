//class pour exporter le tableau en CSV
class TableCSVExporter {
    //Le constructure
    constructor (table, includeHeaders = true) {
        this.table = table;
        this.rows = Array.from(table.querySelectorAll("tr"));
        if (!includeHeaders && this.rows[0].querySelectorAll("th").length) {
            this.rows.shift();
        }
    }
    convertToCSV () {
        const lines = [];
        const numCols = this._findLongestRowLength();
        for (const row of this.rows) {
            let line = "";
            for (let i = 0; i < numCols; i++) {
                if (row.children[i] !== undefined) {
                    line += TableCSVExporter.parseCell(row.children[i]);
                }
                line += (i !== (numCols - 1)) ? ";" : "";
            }
            lines.push(line);
        }
        return lines.join("\n");
    }

    _findLongestRowLength () {
        return this.rows.reduce((l, row) => row.childElementCount > l ? row.childElementCount : l, 0);
    }

    static parseCell (tableCell) {
        let parsedValue = tableCell.textContent;
        parsedValue = parsedValue.replace(/"/g, `""`);
        parsedValue = /[",\n]/.test(parsedValue) ? `"${parsedValue}"` : parsedValue;
        return parsedValue;
    }
}

const dataTable = document.getElementById("dataTable");
const btnExportToCsv = document.getElementById("btnExportToCsv");

btnExportToCsv.addEventListener("click", () => {
    const exporter = new TableCSVExporter(dataTable);
    const csvOutput = exporter.convertToCSV();
    const csvBlob = new Blob([csvOutput], { type: "text/csv;charset=utf-8;" });
    const blobUrl = URL.createObjectURL(csvBlob);
    const anchorElement = document.createElement("a");
    anchorElement.href = blobUrl;
    //le nom du fichier
    anchorElement.download = "F.A.S.T_Signalements.csv";
    anchorElement.click();

    setTimeout(() => {
        URL.revokeObjectURL(blobUrl);
    }, 500);
});