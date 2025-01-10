import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';

const ConfigComponent = () => {
    const [config, setConfig] = useState('');

    useEffect(() => {
        // Fetch current settings
        fetch('/rest/V1/sovendus/config')
            .then(response => response.json())
            .then(data => {
                console.log('Current config:', data);
                setConfig(data.json_config);
            });
    }, []);

    const saveConfig = () => {
        console.log('Saving config:', config);
        fetch('/rest/V1/sovendus/config', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ json_config: config })
        })
        .then(response => response.json())
        .then(data => {
            alert('Settings saved successfully!');
        });
    };

    return (
        <div>
            <textarea
                value={config}
                onChange={(e) => setConfig(e.target.value)}
                rows="20"
                cols="80"
            />
            <br />
            <button onClick={saveConfig}>Save</button>
        </div>
    );
};

ReactDOM.render(<ConfigComponent />, document.getElementById('sovendus-config'));