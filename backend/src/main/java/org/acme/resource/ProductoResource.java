package org.acme.resource;

import org.acme.entity.Producto;
import jakarta.transaction.Transactional;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import java.util.List;

@Path("/api/productos")
@Produces(MediaType.APPLICATION_JSON)
@Consumes(MediaType.APPLICATION_JSON)
public class ProductoResource {

    
    @GET
    public List<Producto> listarActivos() {
        return Producto.list("eliminado", 0);
    }

   
    @GET
    @Path("/{id}")
    public Response obtenerPorId(@PathParam("id") Long id) {
        Producto producto = Producto.findById(id);
        if (producto == null || producto.eliminado == 1) {
            return Response.status(Response.Status.NOT_FOUND).build();
        }
        return Response.ok(producto).build();
    }

  
    @POST
    @Transactional
    public Response crear(Producto producto) {
        if (producto.precio == null || producto.precio.doubleValue() < 0 ||
            producto.stock == null || producto.stock < 0) {
            return Response.status(Response.Status.BAD_REQUEST)
                           .entity("Precio y stock deben ser >= 0").build();
        }
        producto.eliminado = 0;
        producto.persist();
        return Response.status(Response.Status.CREATED).entity(producto).build();
    }

   
    @PUT
    @Path("/{id}")
    @Transactional
    public Response actualizar(@PathParam("id") Long id, Producto datos) {
        Producto producto = Producto.findById(id);
        if (producto == null || producto.eliminado == 1) {
            return Response.status(Response.Status.NOT_FOUND).build();
        }
        
        producto.nombre = datos.nombre;
        producto.proveedor = datos.proveedor;
        producto.fechaVencimiento = datos.fechaVencimiento;
        producto.precio = datos.precio;
        producto.stock = datos.stock;

        return Response.ok(producto).build();
    }

    
    @DELETE
    @Path("/{id}")
    @Transactional
    public Response eliminarLogico(@PathParam("id") Long id) {
        Producto producto = Producto.findById(id);
        if (producto == null) {
            return Response.status(Response.Status.NOT_FOUND).build();
        }
        producto.eliminado = 1;
        return Response.noContent().build();
    }
}