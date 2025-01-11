import { SovendusSettings } from "../sovendus-plugins-commons/admin-frontend/sovendus-app-settings";
import React from "react";
import ReactDOM from "react-dom";
import { SovendusAppSettings } from "../sovendus-plugins-commons/settings/app-settings";

document.addEventListener("DOMContentLoaded", async () => {
  const settingsUrl = "/rest/V1/sovendus/config";
  const containerId = "sovendus-settings-container";
  const container = document.getElementById(containerId);
  if (!container) {
    console.error(`Container with id ${containerId} not found`);
    return;
  }
  const shadowRoot = container.attachShadow({ mode: "open" });
  const reactRoot = document.createElement("div");
  shadowRoot.appendChild(reactRoot);

  const handleSettingsUpdate = async (
    updatedSettings: SovendusAppSettings
  ): Promise<SovendusAppSettings> => {
    console.log("Attempting to save settings...");

    try {
      const response = await fetch(settingsUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        credentials: "same-origin",
        body: JSON.stringify({
          config: JSON.stringify(updatedSettings),
        }),
      });

      if (response.ok) {
        console.log("Settings saved successfully");
        return updatedSettings;
      } else {
        const errorText = await response.text();
        throw new Error(errorText);
      }
    } catch (error) {
      console.error("Save failed:", error);
      throw error;
    }
  };
  try {
    const response = await fetch(settingsUrl);
    const currentSettingsJson = await response.json();
    const currentSettings = JSON.parse(currentSettingsJson);

    console.log("Current settings:", typeof currentSettings, currentSettings);
    if (typeof currentSettings !== "object") {
      console.log("Current settings:", typeof currentSettings, currentSettings);
      throw new Error("Invalid settings format received from server");
    }

    if (!currentSettings?.voucherNetwork) {
      console.log("Current settings:", currentSettings);
      throw new Error("Settings data is missing voucherNetwork properties");
    }
    if (!currentSettings?.optimize) {
      console.log("Current settings:", currentSettings);
      throw new Error("Settings data is missing optimize properties");
    }

    ReactDOM.render(
      React.createElement(SovendusSettings, {
        saveSettings: handleSettingsUpdate,
        currentStoredSettings: currentSettings,
      }),
      reactRoot
    );
  } catch (error) {
    console.error("Failed to fetch settings:", error);
  }
});
