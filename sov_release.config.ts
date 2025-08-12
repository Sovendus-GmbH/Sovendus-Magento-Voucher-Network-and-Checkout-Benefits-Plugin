import type { ReleaseConfig } from "sovendus-release-tool";

const releaseConfig: ReleaseConfig = {
  packages: [
    {
      directory: "./",
      release: {
        version: "2.1.2",
        foldersToZip: [
          {
            input: "SovendusApp",
            output: "releases/%NAME%_%VERSION%.zip",
          },
          {
            input: "SovendusApp",
            output: "releases/%NAME%_latest.zip",
          },
        ],
        versionBumper: [
          {
            filePath: "SovendusApp/Model/Constants.php",
            varName: "SOVENDUS_VERSION",
          },
        ],
      },
      updateDeps: true,
      lint: true,
      build: true,
      test: false,
    },
  ],
};
export default releaseConfig;
