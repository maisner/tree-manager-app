async function fetchAndRenderTrees() {
    try {
        const response = await fetch(`${apiBaseUrl}/category-tree/find-all`);
        if (!response.ok) {
            throw new Error("Failed to fetch tree data");
        }
        const nodes = await response.json();
        renderTree(nodes);
    } catch (error) {
        console.error(error);
        alert("Could not load the tree. Please try again later.");
    }
}

function renderTree(nodes) {
    const container = document.getElementById("tree-container");
    container.innerHTML = "";

    const createTreeHTML = (nodes, parentId = null) => {
        const children = nodes.filter(node => node.parent_id === parentId);
        if (!children.length) return "";

        return `
            <ul>
                ${children
            .map(
                node => `
                        <li class="node-level-${node.level}">
                            <div class="node">
                                <span class="icon">📁</span>
                                <span class="node-name">${node.name}</span>
                                <span class="node-meta">(L: ${node.lft}, R: ${node.rgt}, level: ${node.level})</span>
                                <div class="node-actions">
                                    <button onclick="addChild(${node.id})">➕</button>
                                    <button onclick="editNode(${node.id}, '${node.name}')">✏️</button>
                                    <button onclick="deleteNode(${node.id})">❌</button>
                                </div>
                            </div>
                            ${createTreeHTML(nodes, node.id)}
                        </li>
                    `
            )
            .join("")}
            </ul>
        `;
    };

    container.innerHTML = `<div class="tree">${createTreeHTML(nodes)}</div>`;
}

async function addChild(parentId) {
    const name = prompt("Enter the name of the new child node:");
    if (!name) return;

    try {
        const response = await fetch(`${apiBaseUrl}/category-tree/add-node`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name, parent_id: parentId }),
        });

        if (!response.ok) {
            throw new Error("Failed to add child node");
        }

        fetchAndRenderTrees();
    } catch (error) {
        console.error(error);
        alert("Could not add the child node. Please try again later.");
    }
}

async function addRootNode() {
    const name = prompt("Enter the name of the new root node:");
    if (!name) return;

    try {
        const response = await fetch(`${apiBaseUrl}/category-tree/add-root-node`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name }),
        });

        if (!response.ok) {
            throw new Error("Failed to add root node");
        }

        fetchAndRenderTrees();
    } catch (error) {
        console.error(error);
        alert("Could not add the root node. Please try again later.");
    }
}

async function editNode(nodeId, currentName) {
    const newName = prompt("Enter the new name:", currentName);
    if (!newName) return;

    try {
        const response = await fetch(`${apiBaseUrl}/category-tree/update-node/${nodeId}`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name: newName }),
        });

        if (!response.ok) {
            throw new Error("Failed to update node");
        }

        fetchAndRenderTrees();
    } catch (error) {
        console.error(error);
        alert("Could not update the node. Please try again later.");
    }
}

async function deleteNode(nodeId) {
    if (!confirm("Are you sure you want to delete this node?")) return;

    try {
        const response = await fetch(`${apiBaseUrl}/category-tree/delete-node/${nodeId}`, {
            method: "DELETE",
        });

        if (!response.ok) {
            throw new Error("Failed to delete node");
        }

        fetchAndRenderTrees();
    } catch (error) {
        console.error(error);
        alert("Could not delete the node. Please try again later.");
    }
}

fetchAndRenderTrees();
