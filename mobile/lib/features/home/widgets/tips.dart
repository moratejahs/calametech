import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

class Tip extends StatelessWidget {
  final Color? color;
  final String image;
  final String title;
  final String route;

  const Tip({
    super.key,
    required this.color,
    required this.image,
    required this.title,
    required this.route,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () => context.go(route),
      child: Container(
        margin: const EdgeInsets.all(12.0),
        decoration: BoxDecoration(
          color: color,
          borderRadius: BorderRadius.circular(8.0),
        ),
        child: Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Image.asset(
                image,
                width: 30.0, // specify the width
                height: 30.0, // specify the height
              ),
              const SizedBox(height: 8.0),
              Text(
                title,
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 18.0,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
