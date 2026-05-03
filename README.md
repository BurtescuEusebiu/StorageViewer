# Storage Viewer

## Architectural Overview

The core objective of this architecture is the strict **Separation of Concerns**. By decoupling the data management from the rendering logic, we ensure the system remains maintainable and that the 3D module can be repurposed for other operational visualizations with minimal friction.

### Component Interoperability

The system is structured into three distinct layers that communicate through well-defined interfaces:

#### 1. The Persistence Layer (Backend)
Built on **Laravel**, this layer serves as the single source of truth. 
* **Positioning Calculator Service:** Centralizes the geometric logic, translating logical warehouse coordinates (rows, columns, levels) into a deterministic 3D coordinate system ($x, y, z$). 
* **Consistency:** This ensures that the layout remains consistent across all client sessions and administrative views.

#### 2. The Synchronization Layer (The Bridge)
Utilizing the **TALL stack's** reactive capabilities, this layer manages the state transition between the server and the client.
* **Livewire:** Orchestrates the data flow, delivering localized JSON payloads to the frontend.
* **Alpine.js:** Acts as the high-speed bridge, monitoring the 3D environment's state and invoking server-side methods (via `$wire`) to persist manual adjustments without requiring a page reload.

#### 3. The Rendering Module (3D Engine)
An isolated, modular JavaScript environment powered by **Three.js**. 
* **Data-Agnostic Design:** The engine consumes coordinates and renders meshes without a direct dependency on the database schema. 
* **Sub-Modules:** Internal components handle specialized tasks such as camera optics, lighting, and real-time interaction.
<img width="791" height="771" alt="Arhi drawio" src="https://github.com/user-attachments/assets/7bf740d7-a32f-487b-b391-77cec1645c0b" />

# 3D Scene Hierarchy
The 3D visualization is constructed on a hierarchical tree structure using logical containers. This approach leverages Transformation Inheritance, allowing for the manipulation of entire groups (e.g., moving an entire aisle) without recalculating the individual coordinates of every shelf.

### Scene (Root)
The primary container where all global elements (Camera, Lights, Warehouse) are attached.

### Lights & Environment Groups 
These isolate environmental factors like AmbientLight, GridHelper, and FloorMesh from the business logic objects.

### Warehouse (Group)
Acts as the global coordinate space for the facility. Any transformation applied here repositions the entire inventory.

### StorageRow_N (Group) 
Represents a physical aisle or row. Grouping at this level allows for bulk rotations or displacements.

### StorageCol_M (Group) 
The interactive entity.

### BoxMesh
The visible 3D geometry and material.

### SelectionHelper
A visual feedback layer (e.g., wireframe or edges) toggled during user interaction.

<img width="381" height="831" alt="IerarhieJS drawio" src="https://github.com/user-attachments/assets/3a7c84db-27a1-4269-b1e4-0ebea467a3f0" />
