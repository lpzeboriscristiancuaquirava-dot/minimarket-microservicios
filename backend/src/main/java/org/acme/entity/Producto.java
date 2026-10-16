package org.acme.entity;

import io.quarkus.hibernate.orm.panache.PanacheEntityBase;
import jakarta.persistence.*;
import java.math.BigDecimal;
import java.time.LocalDate;

@Entity
@Table(name = "Producto")
public class Producto extends PanacheEntityBase {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    public Long id;

    @Column(nullable = false, length = 100)
    public String nombre;

    @Column(nullable = false, length = 100)
    public String proveedor;

    @Column(name = "fecha_vencimiento", nullable = false)
    public LocalDate fechaVencimiento;

    @Column(nullable = false, precision = 10, scale = 2)
    public BigDecimal precio;

    @Column(nullable = false)
    public Integer stock;

    @Column(nullable = false)
    public Integer eliminado = 0;
}