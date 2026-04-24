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

