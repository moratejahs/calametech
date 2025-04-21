import 'package:calamitech/config/routing/app_routes.dart';
import 'package:calamitech/features/home/presentation/home_screen.dart';
import 'package:calamitech/features/profile/presentation/profile_screen.dart';
import 'package:flutter/material.dart';

BottomNavigationBar appBottomNav(BuildContext context, String currentRoute) {
  int index = _getTabIndexFromRoute(currentRoute);

  return BottomNavigationBar(
    currentIndex: index,
    onTap: (newIndex) {
      if (index == newIndex) return;

      switch (newIndex) {
        case 0:
          _navigateWithoutAnimation(context, const HomeScreen());
          break;
        case 1:
          _navigateWithoutAnimation(context, const ProfileScreen());
          break;
      }
    },
    items: const [
      BottomNavigationBarItem(
        icon: Icon(Icons.home_outlined),
        activeIcon: Icon(Icons.home),
        label: 'Home',
      ),
      BottomNavigationBarItem(
        icon: Icon(Icons.person_outlined),
        activeIcon: Icon(Icons.person),
        label: 'Profile',
      ),
    ],
  );
}

int _getTabIndexFromRoute(String? route) {
  switch (route) {
    case AppRoutes.profile:
      return 1;
    case AppRoutes.home:
      return 0;
    default:
      return 0;
  }
}

void _navigateWithoutAnimation(BuildContext context, Widget screen) {
  Navigator.of(context).pushReplacement(
    PageRouteBuilder(
      pageBuilder: (_, __, ___) => screen,
      transitionDuration: Duration.zero,
      reverseTransitionDuration: Duration.zero,
    ),
  );
}

