📄 Print Daemon (Windows PDF Silent Printer)

A lightweight Node.js service that runs as a background daemon, receives PDF files from a website/server, and prints them silently to the default system printer using SumatraPDF.

🚀 Features

Detects default printer automatically.

Silently prints PDFs (no UI popup).

Compiles into a single .exe file with pkg – no Node.js installation required on the client.

Extracts SumatraPDF.exe automatically at runtime.

Designed to run hidden in background (like a system daemon).

📦 Prerequisites

Before compiling:

Install Node.js
 (recommend v18+).

Install pkg globally:

npm install -g pkg


Clone or download this repo.

🛠️ Project Structure
print-daemon/
│
├── server.js         # Main server file
├── package.json      # Dependencies & pkg config
└── bin/
    └── SumatraPDF.exe  # Bundled PDF printing binary

⚙️ package.json
{
  "name": "print-daemon",
  "version": "1.0.0",
  "description": "Silent PDF printing daemon with Node.js + SumatraPDF",
  "main": "server.js",
  "scripts": {
    "start": "node server.js",
    "build": "pkg . --targets node18-win-x64 --output PrintDaemon_v1.exe"
  },
  "dependencies": {
    "express": "^4.18.2",
    "body-parser": "^1.20.2"
  },
  "pkg": {
    "assets": [
      "bin/SumatraPDF.exe"
    ]
  }
}

🖥️ server.js (core logic)
const express = require("express");
const bodyParser = require("body-parser");
const { spawn } = require("child_process");
const fs = require("fs");
const path = require("path");
const os = require("os");

const app = express();
const PORT = 3000;

app.use(bodyParser.json());

// Paths
const BIN_NAME = "SumatraPDF.exe";
const BIN_DEST = path.join(os.tmpdir(), BIN_NAME);

// Extract SumatraPDF.exe if not already
if (!fs.existsSync(BIN_DEST)) {
  const snapshotPath = path.join(path.dirname(process.execPath), "bin", BIN_NAME);
  const data = fs.readFileSync(snapshotPath);
  fs.writeFileSync(BIN_DEST, data);
  console.log(`✅ Extracted SumatraPDF to: ${BIN_DEST}`);
}

// API to receive PDF and print
app.post("/print", (req, res) => {
  const pdfPath = req.body.pdfPath;
  if (!pdfPath || !fs.existsSync(pdfPath)) {
    return res.status(400).json({ error: "PDF file not found" });
  }

  const args = [
    "-print-to-default",
    "-silent",
    pdfPath
  ];

  const child = spawn(BIN_DEST, args, { shell: true });
  child.on("error", err => console.error("❌ Print error:", err));
  child.on("exit", code => console.log(`🖨️ Print job finished (code: ${code})`));

  res.json({ success: true, message: "Print job started" });
});

app.listen(PORT, () => {
  console.log(`📡 Print Daemon running on port ${PORT}`);
});

🔨 Build the .exe

Run:

npm install
npm run build


This will generate:

PrintDaemon_v1.exe

🧪 Test before shipping

Run with Node (to check logic):

node server.js


Run compiled exe:

./PrintDaemon_v1.exe


Send a print request (example using curl):

curl -X POST http://localhost:3000/print \
     -H "Content-Type: application/json" \
     -d "{\"pdfPath\":\"C:/Users/YourUser/Desktop/test.pdf\"}"

🖥️ Auto-start on Windows (hidden)

Press Win + R, type shell:startup.

Place a shortcut to PrintDaemon_v1.exe inside that folder.

Right-click → Properties → Run: Minimized (hides console).

Now the daemon runs automatically when Windows starts.

📱 What about Android?

Currently, this method works only on Windows PCs.
On Android, you’d need a native app or something like Termux + CUPS printer service, which is a completely different setup. (Not production-friendly yet.)

✅ Deployment Tips

Always test .exe on a fresh Windows PC (no Node installed).

Ensure default printer is set on the client machine.

If client uses non-default printer, modify spawn args:

["-print-to", "Printer Name", "-silent", pdfPath]


⚡ Done! You now have a plug-and-play PDF print daemon that you can ship to clients.


🖥️ Running as a Background Daemon (Windows)

1. Startup Folder (Easiest)

Press Win + R, type:

shell:startup


Copy your PrintDaemon_v1.exe into this folder (or create a shortcut).

Right-click the shortcut → Properties → Run: Minimized.

✅ Pros: Simple, no admin rights needed.
❌ Cons: User can easily remove it.

2. Windows Task Scheduler (Stealth Mode)

This is more professional: runs invisibly at boot, even if no user logs in.

Open Task Scheduler (taskschd.msc).

Create a new task:

General → Run whether user is logged in or not.

Triggers → At startup.

Actions → Start a program → Browse PrintDaemon_v1.exe.

Conditions → Uncheck Start only if on AC power (if you want on laptops too).

Settings → Enable Run task as soon as possible after a scheduled start.

Save → It will now run silently.

✅ Pros: Runs truly in background, no console window.
❌ Cons: Needs admin to set up.

3. Convert to Windows Service (Using nssm)

If you want real daemon style, turn it into a Windows Service.

Download nssm
.

Open PowerShell as admin:

nssm install PrintDaemon


In the GUI:

Path: C:\path\to\PrintDaemon_v1.exe

Startup type: Automatic

Save → Your service is now managed like any other Windows service.

✅ Pros: Professional, monitored by Windows Service Manager.
❌ Cons: Requires admin + nssm install.