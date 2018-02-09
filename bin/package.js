#!/usr/bin/env node

const fs = require('fs');
const path = require('path');

// Copy the build plugin in a separate folder called /build.
// Remove developer source files, and leave only build files - check with guys on wp.org slack what is needed.
// exclude node_modules/ and vendor/ folder from the build
// Zip the build file to be plugin ready.
// Delete the unzipped stuff from the folder.
