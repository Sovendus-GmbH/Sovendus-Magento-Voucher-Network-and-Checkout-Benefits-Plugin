import React from "react";
import ReactDOM from "react-dom/client";

import { SovendusSettings } from "../sovendus-plugins-commons/admin-frontend/sovendus-app-settings";
import type { SovendusAppSettings } from "../sovendus-plugins-commons/settings/app-settings";

async function loadSetting(): Promise<void> {
  const container = createRootElement();
  if (!container) {
    return;
  }
  const reactRoot = ReactDOM.createRoot(container);
  // container.style.setProperty("transform", "scale(1.5)");
  // container.style.setProperty("transform-origin", "top left");

  try {
    const currentStoredSettings = await getSettings(settingsUrl);
    reactRoot.render(
      React.createElement(SovendusSettings, {
        saveSettings,
        currentStoredSettings,
      }),
    );
  } catch (error) {
    console.error("Failed to fetch settings:", error);
  }
}

const getSettings = async (
  settingsUrl: string,
): Promise<SovendusAppSettings> => {
  const response = await fetch(settingsUrl);
  const currentSettingsJson = await response.json();
  console.log(
    "Current currentSettingsJson:",
    typeof currentSettingsJson,
    currentSettingsJson,
  );
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

const settingsUrl = "/rest/V1/sovendus/config";
const saveSettings = async (
  updatedSettings: SovendusAppSettings,
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

    const responseText = await response.text();
    if (response.ok) {
      console.log("Settings saved successfully", responseText);
      return updatedSettings;
    }
    throw new Error(responseText);
  } catch (error) {
    console.error("Save failed:", error);
    throw error;
  }
};

function createRootElement(): HTMLDivElement | undefined {
  const containerId = "container";
  const container = document.getElementById(containerId) as HTMLDivElement;
  if (!container) {
    console.error(`Container with id ${containerId} not found`);
    return;
  }
  // Hide the original settings form and save button
  (container.firstElementChild as HTMLElement).style.setProperty(
    "display",
    "none",
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
