const express = require("express");
const multer = require("multer");
const printer = require("pdf-to-printer");
const path = require("path");
const fs = require("fs");
const os = require("os");
const child_process = require("child_process");

// ================== SumatraPDF Setup ==================
const BIN_NAME = "SumatraPDF.exe";
const BIN_SOURCE = path.join(path.dirname(process.execPath), "bin", BIN_NAME); // <== works in pkg
const BIN_DEST = path.join(os.tmpdir(), BIN_NAME);

// Extract from pkg virtual fs → real fs
if (!fs.existsSync(BIN_DEST)) {
  try {
    const data = fs.readFileSync(BIN_SOURCE);
    fs.writeFileSync(BIN_DEST, data);
    console.log(`✅ Extracted SumatraPDF to: ${BIN_DEST}`);
  } catch (err) {
    console.error("❌ Failed to extract SumatraPDF:", err);
    process.exit(1);
  }
}

// ================== Ensure uploads dir ==================
const uploadDir = path.join(process.cwd(), "uploads");
if (!fs.existsSync(uploadDir)) fs.mkdirSync(uploadDir, { recursive: true });

const upload = multer({ dest: uploadDir });
const app = express();

// ================== Print Endpoint ==================
app.post("/print", upload.single("file"), async (req, res) => {
  try {
    const filePath = path.resolve(req.file.path);

    const args = [
      "-print-to-default",
      "-silent",
      "-print-settings",
      "noscale",
      filePath
    ];

    console.log("▶ Running:", BIN_DEST, args.join(" "));

    const child = child_process.spawn(BIN_DEST, args, { shell: false });

    child.on("error", (err) => {
      console.error("❌ Failed to start SumatraPDF:", err);
      res.status(500).json({ error: err.message });
    });

    child.on("exit", (code) => {
      if (code === 0) {
        console.log(`✅ Print job completed: ${filePath}`);
        fs.unlink(filePath, (err) => {
          if (err) console.warn("⚠️ Could not delete file:", err);
        });
        res.json({ success: true });
      } else {
        console.error(`❌ SumatraPDF exited with code ${code}`);
        res.status(500).json({ error: `SumatraPDF exited with code ${code}` });
      }
    });
  } catch (err) {
    console.error("❌ Print error:", err);
    res.status(500).json({ error: err.message });
  }
});


// ================== Start Daemon ==================
const PORT = 9000;
app.listen(PORT, () => {
  console.log("=======================================");
  console.log(" 🖨️  Print Daemon running");
  console.log(` 🌐  Listening at http://localhost:${PORT}`);
  console.log(" 📄  POST PDFs to /print to print silently");
  console.log("=======================================");
});
