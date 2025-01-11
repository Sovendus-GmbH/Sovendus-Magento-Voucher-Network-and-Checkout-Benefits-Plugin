import { SovendusSettings } from "../sovendus-plugins-commons/admin-frontend/sovendus-app-settings";
import React from "react";
import ReactDOM from "react-dom";
import { SovendusAppSettings } from "../sovendus-plugins-commons/settings/app-settings";

async function loadSetting() {
  const settingsUrl = "/rest/V1/sovendus/config";
  const containerId = "container";
  const container = document.getElementById(containerId);
  if (!container) {
    console.error(`Container with id ${containerId} not found`);
    return;
  }
  const reactRoot = createReactRoot(container);

  const saveSettings = async (
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
    const currentStoredSettings = await getSettings(settingsUrl);
    ReactDOM.render(
      React.createElement(SovendusSettings, {
        saveSettings,
        currentStoredSettings,
      }),
      reactRoot
    );
  } catch (error) {
    console.error("Failed to fetch settings:", error);
  }
}

const getSettings = async (
  settingsUrl: string
): Promise<SovendusAppSettings> => {
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
  return currentSettings;
};

function createReactRoot(container: HTMLElement) {
  // Hide the original settings form and save button
  (container.firstElementChild as HTMLElement).style.setProperty(
    "display",
    "none"
  );
  (
    document.querySelector("button.save") as HTMLButtonElement
  ).style.setProperty("display", "none");

  const settingsContainer = document.createElement("div");
  container.appendChild(settingsContainer);
  // const shadowRoot = settingsContainer.attachShadow({ mode: "open" });
  // const reactRoot = document.createElement("div");
  // shadowRoot.appendChild(reactRoot);
  return settingsContainer;
  // return reactRoot;
}
void loadSetting();
